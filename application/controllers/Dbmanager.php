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
        @unlink($tmp);
        $cmd = sprintf(
            "MYSQL_PWD=%s %s --single-transaction --quick --skip-lock-tables --default-character-set=utf8mb4 -h%s -P%s -u%s %s > %s 2>&1",
            escapeshellarg((string) $this->db->password),
            escapeshellarg($binary),
            escapeshellarg((string) $this->db->hostname),
            escapeshellarg((string) ($this->db->port ?: "3306")),
            escapeshellarg((string) $this->db->username),
            escapeshellarg((string) $this->db->database),
            escapeshellarg($tmp)
        );
        $out = [];
        $code = 1;
        @exec($cmd, $out, $code);
        if ($code !== 0 || !is_file($tmp) || filesize($tmp) < 1024) {
            @unlink($tmp);
            return null;
        }

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
                return $this->output_json(["type" => "file", "message" => "File data berhasil dibackup"]);
            }
        }
        $this->load->library("zip");
        $this->zip->read_dir("uploads");
        $this->zip->archive($target);
        $this->output_json(["type" => "file", "message" => "File data berhasil dibackup"]);
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
