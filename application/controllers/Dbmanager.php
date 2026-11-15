<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Dbmanager extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
        } else if (!($this->ion_auth->is_admin())) {
            show_error("Hanya Admin yang boleh mengakses halaman ini", 403, "Akses dilarang");
        }
        $this->load->library("upload");
        $this->load->model("Settings_model", "settings");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->helper("directory");
    }
    public function output_json($data, $encode = true)
    {
        if ($encode) {
            $data = json_encode($data);
        }
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Backup dan Restore", "subjudul" => "Backup Semua Database dan File", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $list = directory_map("./backups/");
        $arrFile = [];
        foreach ($list as $key => $value) {
            $nfile = explode(".", $value);
            $nama = $nfile[0];
            $type = $nfile[1];
            $tgl = filemtime("./backups/" . $value);
            $size = $this->formatSizeUnits(filesize("./backups/" . $value));
            $arrFile[$key] = ["type" => $type, "nama" => $nama, "tgl" => $tgl, "size" => $size, "src" => $value];
        }
        $data["list"] = $arrFile;
        $data["tables"] = $this->db->list_tables();
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("setting/db");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function manage()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Bersihkan Data", "subjudul" => "Hapus Data", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data_tables = [];
        $tables = $this->db->list_tables();
        foreach ($tables as $table) {
            $data_tables[$table] = $this->settings->toJSON($table);
        }
        $data["tables"] = $data_tables;
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("setting/manage");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function truncate()
    {
        $tables = $this->db->list_tables();
        $this->settings->truncate($tables);
        $this->output_json(["status" => true]);
    }
    public function backupDb()
    {
        $base = FCPATH . "backups/backup-db-" . date("Y-m-d-H-i-s");
        $dump = $this->find_binary(["mariadb-dump", "mysqldump"]);
        $file = $dump !== null ? $this->backup_database_with_cli($dump, $base) : null;
        if ($file === null) {
            $file = $this->backup_database_with_dbutil($base . ".sql");
        }
        if ($file === null) {
            return $this->output_json([
                "type" => "database",
                "status" => false,
                "message" => "Backup database gagal. Periksa ruang disk dan izin folder backups/.",
            ]);
        }
        return $this->output_json([
            "type" => "database",
            "message" => "Database berhasil dibackup",
            "file" => basename($file),
        ]);
    }

    /**
     * Stream the dump through mariadb-dump/mysqldump. Loading the whole dump in
     * PHP (CI's dbutil does that, plus zip compression) needs several hundred MB
     * for this database and dies on the default memory limit.
     */
    private function backup_database_with_cli($binary, $base)
    {
        $tmp = $base . ".sql.part";
        $err = $base . ".err";
        @unlink($tmp);
        @unlink($err);
        // stderr goes to its own file: the client prints its warnings on stderr
        // and merging them into the dump corrupts the first line.
        $cmd = sprintf(
            "MYSQL_PWD=%s %s --single-transaction --quick --skip-lock-tables --default-character-set=utf8mb4 -h%s -P%s -u%s %s > %s 2> %s",
            escapeshellarg((string) $this->db->password),
            escapeshellarg($binary),
            escapeshellarg((string) $this->db->hostname),
            escapeshellarg((string) ($this->db->port ?: "3306")),
            escapeshellarg((string) $this->db->username),
            escapeshellarg((string) $this->db->database),
            escapeshellarg($tmp),
            escapeshellarg($err)
        );
        $out = [];
        $code = 1;
        @exec($cmd, $out, $code);
        if ($code !== 0 || !is_file($tmp) || filesize($tmp) < 1024) {
            @unlink($tmp);
            @unlink($err);
            return null;
        }
        @unlink($err);

        $gzip = $this->find_binary(["gzip"]);
        if ($gzip === null) {
            $plain = $base . ".sql";
            return @rename($tmp, $plain) ? $plain : null;
        }
        $target = $base . ".sql.gz";
        @unlink($target);
        $code = 1;
        @exec(escapeshellarg($gzip) . " -c " . escapeshellarg($tmp) . " > " . escapeshellarg($target), $gzOut, $code);
        @unlink($tmp);
        if ($code === 0 && is_file($target) && filesize($target) > 0) {
            return $target;
        }
        @unlink($target);
        return null;
    }

    /**
     * Fallback for hosts without the client binaries: dump one table at a time
     * and append, so peak memory is the largest table instead of the whole DB.
     */
    private function backup_database_with_dbutil($target)
    {
        $this->load->dbutil();
        @unlink($target);
        $ok = false;
        foreach ($this->db->list_tables() as $table) {
            $prefs = [
                "tables" => [$table],
                "ignore" => [],
                "format" => "txt",
                "filename" => "backup.sql",
                "add_drop" => TRUE,
                "add_insert" => TRUE,
                "newline" => "\n",
            ];
            $sql = $this->dbutil->backup($prefs);
            if (!is_string($sql) || $sql === "") {
                continue;
            }
            if (file_put_contents($target, $sql, FILE_APPEND) !== false) {
                $ok = true;
            }
        }
        return $ok && is_file($target) && filesize($target) > 0 ? $target : null;
    }

    public function backupData()
    {
        $target = FCPATH . "backups/backup-file-" . date("Y-m-d-H-i-s") . ".zip";
        $zip = $this->find_binary(["zip"]);
        if ($zip !== null) {
            $out = [];
            $code = 1;
            @exec(
                "cd " . escapeshellarg(FCPATH) . " && " . escapeshellarg($zip) . " -r -q -9 " . escapeshellarg($target) . " uploads",
                $out,
                $code
            );
            if ($code === 0 && is_file($target)) {
                return $this->output_json(["type" => "file", "message" => "Folder uploads berhasil dibackup", "file" => basename($target)]);
            }
        }
        $this->load->library("zip");
        $this->zip->read_dir("uploads");
        $this->zip->archive($target);
        $this->output_json(["type" => "file", "message" => "Folder uploads berhasil dibackup", "file" => basename($target)]);
    }

    public function restore()
    {
        $src = trim((string) $this->input->post("src", true));
        $file = null;
        $label = "";
        if (isset($_FILES["restore_file"]) && is_uploaded_file($_FILES["restore_file"]["tmp_name"])) {
            $label = (string) $_FILES["restore_file"]["name"];
            $ext = strtolower(pathinfo($label, PATHINFO_EXTENSION));
            if (!in_array($ext, ["sql", "gz", "zip"], true)) {
                return $this->output_json(["status" => false, "message" => "Format file harus .sql, .sql.gz, atau .zip."]);
            }
            $file = $_FILES["restore_file"]["tmp_name"];
        } else if ($src !== "") {
            $file = $this->backup_path($src);
            $label = $src;
        }
        if ($file === null) {
            return $this->output_json(["status" => false, "message" => "Pilih backup dari riwayat atau unggah file restore."]);
        }

        $prepared = $this->prepare_restore($file, $label);
        if (isset($prepared["error"])) {
            return $this->output_json(["status" => false, "message" => $prepared["error"]]);
        }

        if ($prepared["type"] === "uploads") {
            $result = $this->restore_uploads($prepared["path"]);
        } else {
            $result = $this->restore_database($prepared["path"]);
        }
        if (!empty($prepared["temp"])) {
            @unlink($prepared["path"]);
        }
        return $this->output_json(["status" => $result[0], "message" => $result[1]]);
    }

    private function backup_path($src)
    {
        $src = basename(trim((string) $src));
        if ($src === "" || $src === "." || $src === "..") {
            return null;
        }
        $path = FCPATH . "backups/" . $src;
        return is_file($path) ? $path : null;
    }

    /**
     * Work out what a restore file contains: a SQL dump (plain, gzip, or inside
     * a zip) or a zip of the uploads folder. Returns a temp path for archives.
     */
    private function prepare_restore($file, $label)
    {
        $ext = strtolower(pathinfo($label, PATHINFO_EXTENSION));
        if ($ext === "zip") {
            $zip = new ZipArchive();
            if ($zip->open($file) !== true) {
                return ["error" => "File zip tidak dapat dibuka."];
            }
            $sqlEntry = null;
            $hasUploads = false;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = str_replace("\\", "/", (string) $zip->getNameIndex($i));
                if (substr($name, -1) === "/") {
                    continue;
                }
                if ($sqlEntry === null && preg_match('/\.sql$/i', $name)) {
                    $sqlEntry = $name;
                }
                if (strpos($name, "uploads/") === 0) {
                    $hasUploads = true;
                }
            }
            if ($sqlEntry !== null) {
                $tmp = tempnam(sys_get_temp_dir(), "gcb_restore_");
                $in = $zip->getStream($sqlEntry);
                $out = $in ? fopen($tmp, "wb") : false;
                if ($in && $out) {
                    while (!feof($in)) {
                        fwrite($out, fread($in, 1048576));
                    }
                    fclose($in);
                    fclose($out);
                    $zip->close();
                    return ["type" => "sql", "path" => $tmp, "temp" => true];
                }
                $zip->close();
                @unlink($tmp);
                return ["error" => "Gagal mengekstrak file .sql dari zip."];
            }
            $zip->close();
            if ($hasUploads) {
                return ["type" => "uploads", "path" => $file, "temp" => false];
            }
            return ["error" => "Zip tidak berisi file .sql atau folder uploads/."];
        }
        if ($ext === "gz") {
            $tmp = tempnam(sys_get_temp_dir(), "gcb_restore_");
            $in = @gzopen($file, "rb");
            $out = $in ? fopen($tmp, "wb") : false;
            if (!$in || !$out) {
                @unlink($tmp);
                return ["error" => "File .gz tidak dapat dibuka."];
            }
            while (!gzeof($in)) {
                fwrite($out, gzread($in, 1048576));
            }
            gzclose($in);
            fclose($out);
            return ["type" => "sql", "path" => $tmp, "temp" => true];
        }
        return ["type" => "sql", "path" => $file, "temp" => false];
    }

    private function restore_database($sqlPath)
    {
        $mysql = $this->find_binary(["mariadb", "mysql"]);
        if ($mysql === null) {
            return [false, "Binary mariadb/mysql tidak tersedia di server, restore tidak dapat dijalankan."];
        }
        $clean = tempnam(sys_get_temp_dir(), "gcb_restore_");
        $copyError = $this->copy_sql_skipping_noise($sqlPath, $clean);
        if ($copyError !== null) {
            @unlink($clean);
            return [false, $copyError];
        }
        $safety = null;
        $dump = $this->find_binary(["mariadb-dump", "mysqldump"]);
        if ($dump !== null) {
            $safety = $this->backup_database_with_cli($dump, FCPATH . "backups/backup-sebelum-restore-" . date("Y-m-d-H-i-s"));
        }
        $err = $clean . ".err";
        $cmd = sprintf(
            "MYSQL_PWD=%s %s -h%s -P%s -u%s %s < %s 2> %s",
            escapeshellarg((string) $this->db->password),
            escapeshellarg($mysql),
            escapeshellarg((string) $this->db->hostname),
            escapeshellarg((string) ($this->db->port ?: "3306")),
            escapeshellarg((string) $this->db->username),
            escapeshellarg((string) $this->db->database),
            escapeshellarg($clean),
            escapeshellarg($err)
        );
        $out = [];
        $code = 1;
        @exec($cmd, $out, $code);
        $detail = "";
        if (is_file($err)) {
            $lines = file($err, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            foreach ($lines as $line) {
                if (stripos($line, "WARNING:") === 0) {
                    continue;
                }
                $detail = trim($line);
                if ($detail !== "") {
                    break;
                }
            }
            @unlink($err);
        }
        @unlink($clean);
        if ($code !== 0) {
            return [false, "Restore database gagal (kode " . $code . ")." . ($detail !== "" ? " " . $detail : "")];
        }
        $message = "Database berhasil direstore.";
        if ($safety !== null) {
            $message .= " Backup otomatis sebelum restore: " . basename($safety);
        }
        return [true, $message];
    }

    /**
     * Copy the dump for the client, skipping leading non-SQL lines. Backups
     * made by older versions can start with a client warning such as
     * "WARNING: option --ssl-verify-server-cert ...", which makes the server
     * reject the very first statement.
     */
    private function copy_sql_skipping_noise($source, $target)
    {
        $in = @fopen($source, "rb");
        if (!$in) {
            return "File SQL tidak dapat dibaca.";
        }
        $out = @fopen($target, "wb");
        if (!$out) {
            fclose($in);
            return "Tidak dapat menulis file sementara untuk restore.";
        }
        $first = true;
        while (($line = fgets($in)) !== false) {
            if ($first) {
                $trim = ltrim($line);
                if (stripos($trim, "WARNING:") === 0 || preg_match('/^-{3,}\s*$/', $trim)) {
                    continue;
                }
                $first = false;
            }
            fwrite($out, $line);
        }
        fclose($in);
        fclose($out);
        return null;
    }

    private function restore_uploads($zipPath)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return [false, "File zip tidak dapat dibuka."];
        }
        $root = rtrim(FCPATH, "/") . "/uploads/";
        $restored = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = str_replace("\\", "/", (string) $zip->getNameIndex($i));
            if (substr($name, -1) === "/" || strpos($name, "uploads/") !== 0) {
                continue;
            }
            $relative = substr($name, strlen("uploads/"));
            if ($relative === "" || strpos($relative, "..") !== false) {
                continue;
            }
            $target = $root . $relative;
            if (!is_dir(dirname($target))) {
                @mkdir(dirname($target), 0775, true);
            }
            $in = $zip->getStream($name);
            if (!$in) {
                continue;
            }
            $out = @fopen($target, "wb");
            if ($out) {
                while (!feof($in)) {
                    fwrite($out, fread($in, 1048576));
                }
                fclose($out);
                $restored++;
            }
            fclose($in);
        }
        $zip->close();
        return [true, "Restore file selesai, " . $restored . " file dipulihkan ke folder uploads."];
    }

    private function find_binary(array $names)
    {
        if (!function_exists("shell_exec") || !is_callable("exec")) {
            return null;
        }
        foreach ($names as $name) {
            $path = @shell_exec("command -v " . escapeshellarg($name) . " 2>/dev/null");
            $path = is_string($path) ? trim($path) : "";
            if ($path !== "") {
                return $path;
            }
        }
        return null;
    }
    public function hapusBackup($src)
    {
        if (unlink("./backups/" . $src)) {
            $this->output_json(["status" => true, "message" => "Backup berhasil dihapus"]);
        } else {
            $this->output_json(["status" => false, "message" => "Gagal menghapus backup"]);
        }
    }
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . " GB";
            return $bytes;
        }
        if ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . " MB";
            return $bytes;
        }
        if ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . " KB";
            return $bytes;
        }
        if ($bytes > 1) {
            $bytes .= " bytes";
            return $bytes;
        }
        if ($bytes == 1) {
            $bytes .= " byte";
            return $bytes;
        }
        $bytes = "0 bytes";
        return $bytes;
    }
}
