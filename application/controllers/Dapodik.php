<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");

class Dapodik extends CI_Controller
{
    private $dapodik = ["url" => "", "token" => "", "npsn" => "", "last_sync" => null];

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
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->helper("directory");
        $this->ensure_schema();
        $this->dapodik = $this->dapodik_config();
    }

    /**
     * The endpoint and token used to live in config.php only. They are stored
     * on the setting row now so an admin can change them from the UI; the
     * config.php values remain as a fallback for existing installs.
     */
    private function ensure_schema()
    {
        if (schema_has_column("setting", "dapodik_url")) {
            return;
        }
        $this->load->dbforge();
        $this->dbforge->add_column("setting", [
            "dapodik_url" => ["type" => "VARCHAR", "constraint" => 255, "null" => TRUE, "default" => NULL],
            "dapodik_token" => ["type" => "VARCHAR", "constraint" => 255, "null" => TRUE, "default" => NULL],
            "dapodik_last_sync" => ["type" => "DATETIME", "null" => TRUE, "default" => NULL],
        ]);
    }

    private function dapodik_config()
    {
        $setting = $this->settings->getSetting();
        $url = $setting != null && isset($setting->dapodik_url) ? trim((string) $setting->dapodik_url) : "";
        $token = $setting != null && isset($setting->dapodik_token) ? trim((string) $setting->dapodik_token) : "";
        if ($url === "") {
            $url = (string) $this->config->item("DAPODIK_WEBSERVICE_URL");
        }
        if ($token === "") {
            $token = (string) $this->config->item("DAPODIK_WEBSERVICE_TOKEN");
        }
        return [
            "url" => rtrim(trim($url), "/"),
            "token" => trim($token),
            "npsn" => $setting != null && isset($setting->npsn) ? trim((string) $setting->npsn) : "",
            "last_sync" => $setting != null && isset($setting->dapodik_last_sync) ? $setting->dapodik_last_sync : null,
        ];
    }

    private function config_error()
    {
        $missing = [];
        if ($this->dapodik["url"] === "") {
            $missing[] = "URL webservice";
        }
        if ($this->dapodik["token"] === "") {
            $missing[] = "token";
        }
        if ($this->dapodik["npsn"] === "") {
            $missing[] = "NPSN (lengkapi di menu Profile Sekolah)";
        }
        if (count($missing) === 0) {
            return null;
        }
        return "Pengaturan Dapodik belum lengkap: " . implode(", ", $missing) . ".";
    }

    public function previewGtk()
    {
        $fetched = $this->fetch_rows("/WebService/getGtk", 60);
        if (isset($fetched["error"])) {
            return $this->output_json(["status" => false, "message" => $fetched["error"]]);
        }
        $plan = $this->build_gtk_plan($fetched["rows"]);
        $insert = 0;
        $update = 0;
        foreach ($plan["items"] as $item) {
            if ($item["action"] === "insert") {
                $insert++;
            } else {
                $update++;
            }
        }
        return $this->output_json([
            "status" => true,
            "summary" => [
                "total" => count($fetched["rows"]),
                "insert" => $insert,
                "update" => $update,
                "invalid" => count($plan["invalid"]),
            ],
            "rows" => array_slice($plan["items"], 0, 500),
            "invalid" => $plan["invalid"],
        ]);
    }

    public function previewSiswa()
    {
        $fetched = $this->fetch_rows("/WebService/getPesertaDidik", 90);
        if (isset($fetched["error"])) {
            return $this->output_json(["status" => false, "message" => $fetched["error"]]);
        }
        $plan = $this->build_siswa_plan($fetched["rows"]);
        $baru = 0;
        $update = 0;
        foreach ($plan["items"] as $item) {
            if ($item["is_new"]) {
                $baru++;
            } else {
                $update++;
            }
        }
        return $this->output_json([
            "status" => true,
            "summary" => [
                "total" => count($fetched["rows"]),
                "insert" => $baru,
                "update" => $update,
                "invalid" => count($plan["invalid"]),
                "classes" => count($plan["classes"]),
            ],
            "classes" => $plan["classes"],
            "rows" => array_slice($plan["items"], 0, 500),
            "invalid" => $plan["invalid"],
        ]);
    }

    protected function fetch_rows($path, $timeout)
    {
        $error = $this->config_error();
        if ($error !== null) {
            return ["error" => $error];
        }
        try {
            $response = $this->get_dapodik_httpclient()->get($path, ["timeout" => $timeout]);
        } catch (\Throwable $e) {
            return ["error" => "Gagal terhubung ke webservice: " . $e->getMessage()];
        }
        $raw = (string) $response->getBody();
        $json = json_decode($raw);
        if (!isset($json->rows) || !is_array($json->rows)) {
            return ["error" => "Respons webservice tidak dikenali (field rows tidak ada)"
                . $this->response_hint($response->getHeaderLine("Content-Type"), $raw)
                . " Periksa URL dan token Dapodik."];
        }
        if (count($json->rows) === 0) {
            return ["error" => "Webservice tidak mengembalikan data untuk NPSN " . $this->dapodik["npsn"] . ". Periksa NPSN dan token."];
        }
        return ["rows" => $json->rows];
    }

    /**
     * Short description of a response that is not the expected JSON payload,
     * so the admin can tell a wrong URL from a wrong token.
     */
    private function response_hint($contentType, $raw)
    {
        $hint = "";
        if ($contentType !== "") {
            $hint .= " (Content-Type: " . $contentType . ")";
        }
        $snippet = trim(preg_replace("/\s+/", " ", strip_tags(substr($raw, 0, 120))));
        if ($snippet !== "") {
            $hint .= ': "' . $snippet . '"';
        }
        return $hint . ".";
    }

    /**
     * Dry run for the GTK import: what would be inserted, what updated, and
     * which rows can not be imported at all.
     */
    private function build_gtk_plan(array $rows)
    {
        $items = [];
        $invalid = [];
        $nips = [];
        foreach ($rows as $value) {
            $nip = isset($value->nip) ? trim((string) $value->nip) : "";
            $nama = isset($value->nama) ? $value->nama : "-";
            if (strlen($nip) < 5) {
                $invalid[] = ["nama" => $nama, "nip" => $nip, "reason" => "NIP kosong atau kurang dari 5 digit"];
                continue;
            }
            $nips[] = $nip;
            $items[] = [
                "nip" => $nip,
                "nama" => $nama,
                "jenis_kelamin" => isset($value->jenis_kelamin) ? $value->jenis_kelamin : "",
                "agama" => isset($value->agama_id_str) ? $value->agama_id_str : "",
                "tahun_ajaran_id" => isset($value->tahun_ajaran_id) ? $value->tahun_ajaran_id : "",
            ];
        }

        $existingGuru = [];
        $existingUser = [];
        if (count($nips) > 0) {
            foreach ($this->db->select("nip")->from("master_guru")->where_in("nip", $nips)->get()->result() as $row) {
                $existingGuru[$row->nip] = true;
            }
            foreach ($this->db->select("id, username")->from("users")->where_in("username", $nips)->get()->result() as $row) {
                $existingUser[$row->username] = $row->id;
            }
        }

        $plan = [];
        foreach ($items as $item) {
            $plan[] = [
                "nip" => $item["nip"],
                "nama" => $item["nama"],
                "jenis_kelamin" => $item["jenis_kelamin"],
                "agama" => $item["agama"],
                "action" => isset($existingGuru[$item["nip"]]) ? "update" : "insert",
                "user" => isset($existingUser[$item["nip"]]) ? "ada" : "baru",
            ];
        }
        return ["items" => $plan, "invalid" => $invalid];
    }

    /**
     * Dry run for the student import. The class summary is the important part:
     * it shows, per rombel, whether the class already exists, which level and
     * jurusan it maps to, and how many students would be added.
     */
    private function build_siswa_plan(array $rows)
    {
        $items = [];
        $invalid = [];
        $nisns = [];
        $kodes = [];

        foreach ($rows as $value) {
            $rombel = isset($value->nama_rombel) ? trim((string) $value->nama_rombel) : "";
            $nisn = isset($value->nisn) ? trim((string) $value->nisn) : "";
            $nis = isset($value->nipd) ? trim((string) $value->nipd) : "";
            $nama = isset($value->nama) ? $value->nama : "-";
            if ($nis === "" || $nisn === "") {
                $invalid[] = ["nama" => $nama, "nisn" => $nisn, "nis" => $nis, "reason" => "NIS atau NISN kosong"];
                continue;
            }
            $kode = strtolower(preg_replace("/\s+/", "_", $rombel));
            list($level, $jurusanKode, $jurusanNama) = $this->parse_rombel($rombel);
            $nisns[] = $nisn;
            $kodes[$kode] = true;
            $items[] = [
                "nisn" => $nisn,
                "nis" => $nis,
                "nama" => $nama,
                "rombel" => $rombel,
                "kode" => $kode,
                "level" => $level,
                "jurusan_kode" => $jurusanKode,
                "jurusan_nama" => $jurusanNama,
            ];
        }

        $existingSiswa = [];
        if (count($nisns) > 0) {
            foreach ($this->db->select("id_siswa, nisn")->from("master_siswa")->where_in("nisn", $nisns)->get()->result() as $row) {
                $existingSiswa[$row->nisn] = $row->id_siswa;
            }
        }

        $existingKelas = [];
        $jurusanByKode = [];
        if (count($kodes) > 0) {
            foreach ($this->db->select("id_kelas, kode_kelas, level_id, jurusan_id")->from("master_kelas")->where_in("kode_kelas", array_keys($kodes))->get()->result() as $row) {
                $existingKelas[$row->kode_kelas] = $row;
            }
            $jurusanKodes = array_values(array_unique(array_map(function ($item) { return $item["jurusan_kode"]; }, $items)));
            foreach ($this->db->select("id_jurusan, kode_jurusan")->from("master_jurusan")->where_in("kode_jurusan", $jurusanKodes)->get()->result() as $row) {
                $jurusanByKode[$row->kode_jurusan] = $row->id_jurusan;
            }
        }

        $classes = [];
        $plan = [];
        foreach ($items as $item) {
            $kode = $item["kode"];
            if (!isset($classes[$kode])) {
                $kelas = isset($existingKelas[$kode]) ? $existingKelas[$kode] : null;
                $jurusanId = isset($jurusanByKode[$item["jurusan_kode"]]) ? $jurusanByKode[$item["jurusan_kode"]] : null;
                $warnings = [];
                if ($kelas === null) {
                    $warnings[] = "Kelas belum ada, akan dibuat.";
                } else {
                    if ((string) $kelas->level_id !== (string) $item["level"]) {
                        $warnings[] = "Level tersimpan " . $kelas->level_id . ", Dapodik " . $item["level"] . ".";
                    }
                    if ($jurusanId !== null && (string) $kelas->jurusan_id !== (string) $jurusanId) {
                        $warnings[] = "Jurusan kelas tersimpan berbeda dengan Dapodik.";
                    }
                }
                $classes[$kode] = [
                    "nama_rombel" => $item["rombel"],
                    "kode" => $kode,
                    "level" => $item["level"],
                    "jurusan_kode" => $item["jurusan_kode"],
                    "jurusan_nama" => $item["jurusan_nama"],
                    "jurusan_baru" => $jurusanId === null,
                    "kelas_ada" => $kelas !== null,
                    "siswa" => 0,
                    "baru" => 0,
                    "update" => 0,
                    "warnings" => $warnings,
                ];
            }
            $isNew = !isset($existingSiswa[$item["nisn"]]);
            $classes[$kode]["siswa"]++;
            $classes[$kode][$isNew ? "baru" : "update"]++;
            $item["is_new"] = $isNew;
            $item["id_siswa"] = $isNew ? null : $existingSiswa[$item["nisn"]];
            $plan[] = $item;
        }

        return ["items" => $plan, "classes" => array_values($classes), "invalid" => $invalid];
    }

    private function parse_rombel($rombel)
    {
        $parts = preg_split("/\s+/", trim($rombel));
        $level = 10;
        if (isset($parts[0]) && $parts[0] === "XI") {
            $level = 11;
        } elseif (isset($parts[0]) && $parts[0] === "XII") {
            $level = 12;
        }
        if (count($parts) > 2) {
            return [$level, $parts[1], $parts[1]];
        }
        return [$level, "NONJURUSAN", "Non Jurusan"];
    }

    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = [
            "judul" => "DAPODIK",
            "subjudul" => "TARIK DATA DAPODIK",
            "user" => $user,
            "setting" => $setting,
            "profile" => $this->dashboard->getProfileAdmin($user->id),
            "dapodik" => $this->dapodik,
            "dapodik_ready" => $this->config_error() === null,
        ];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();

        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("setting/dapodik", $data);
        $this->load->view("_templates/dashboard/_footer");
    }

    public function saveSettings()
    {
        $url = trim((string) $this->input->post("dapodik_url", true));
        $token = trim((string) $this->input->post("dapodik_token", true));
        if ($url === "" || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return $this->output_json(["status" => false, "message" => "URL webservice tidak valid."]);
        }
        if ($token === "") {
            return $this->output_json(["status" => false, "message" => "Token webservice wajib diisi."]);
        }
        $this->db->where("id_setting", 1);
        $saved = $this->db->update("setting", [
            "dapodik_url" => rtrim($url, "/"),
            "dapodik_token" => $token,
        ]);
        return $this->output_json([
            "status" => (bool) $saved,
            "message" => $saved ? "Pengaturan Dapodik disimpan." : "Gagal menyimpan pengaturan.",
        ]);
    }

    public function testConnection()
    {
        $error = $this->config_error();
        if ($error !== null) {
            return $this->output_json(["status" => false, "message" => $error]);
        }
        try {
            $response = $this->get_dapodik_httpclient()->get("/WebService/getGtk", ["timeout" => 15, "http_errors" => false]);
            $code = $response->getStatusCode();
            $contentType = $response->getHeaderLine("Content-Type");
            $raw = (string) $response->getBody();
            if ($code < 200 || $code >= 300) {
                return $this->output_json([
                    "status" => false,
                    "message" => "Webservice membalas HTTP " . $code . ". Periksa URL, token, dan NPSN.",
                ]);
            }
            $body = json_decode($raw);
            if (!isset($body->rows) || !is_array($body->rows)) {
                return $this->output_json([
                    "status" => false,
                    "message" => "Koneksi berhasil (HTTP " . $code . "), tetapi respons bukan data webservice Dapodik"
                        . $this->response_hint($contentType, $raw)
                        . " Periksa URL webservice dan pastikan alamat /WebService dapat diakses.",
                ]);
            }
            $jumlah = count($body->rows);
            if ($jumlah === 0) {
                return $this->output_json([
                    "status" => false,
                    "message" => "Koneksi berhasil (HTTP " . $code . "), tetapi tidak ada data GTK untuk NPSN "
                        . $this->dapodik["npsn"] . ". Periksa NPSN dan token.",
                ]);
            }
            return $this->output_json([
                "status" => true,
                "message" => "Koneksi berhasil (HTTP " . $code . "), " . $jumlah . " data GTK terbaca.",
            ]);
        } catch (\Throwable $e) {
            return $this->output_json(["status" => false, "message" => "Gagal terhubung: " . $e->getMessage()]);
        }
    }

    public function tarikDataGtk()
    {
        $fetched = $this->fetch_rows("/WebService/getGtk", 120);
        if (isset($fetched["error"])) {
            return $this->output_json(["error" => $fetched["error"]]);
        }
        $json = (object) ["rows" => $fetched["rows"]];

        $nama_fails = [];
        $berhasil = 0;
        $this->db->trans_start();
        foreach ($json->rows as $value) {
            $nama = isset($value->nama) ? $value->nama : "-";
            if (!isset($value->nip) || strlen($value->nip) < 5) {
                array_push($nama_fails, "MERR: " . $nama);
                continue;
            }
            $data = [
                "nip" => $value->nip,
                "nama_guru" => $nama,
                "username" => $value->nip,
                "password" => "123456" . (isset($value->tahun_ajaran_id) ? $value->tahun_ajaran_id : ""),
                "foto" => "uploads/profiles/" . $value->nip . ".jpg",
                "jenis_kelamin" => isset($value->jenis_kelamin) ? $value->jenis_kelamin : "",
                "agama" => isset($value->agama_id_str) ? $value->agama_id_str : "",
            ];
            $userGuruRow = $this->db->select("id")->from("users")->where("username", $value->nip)->get()->row();
            if (isset($userGuruRow)) {
                $data["id_user"] = $userGuruRow->id;
            }
            $rowGuru = $this->db->select("nip, username, password")->from("master_guru")->where("nip", $value->nip)->get()->row();
            if ($rowGuru !== null) {
                $update = $data;
                if (trim((string) $rowGuru->username) !== "") {
                    unset($update["username"]);
                }
                if (trim((string) $rowGuru->password) !== "") {
                    unset($update["password"]);
                }
                $act = $this->master->update("master_guru", $update, "nip", $value->nip);
            } else {
                $act = $this->master->create("master_guru", $data);
            }
            if (!$this->ion_auth->username_check($data["username"])) {
                $namaParts = explode(" ", $nama);
                if (!$this->ion_auth->register($data["username"], $data["password"], $value->nip . "@guru.com", [
                    "first_name" => $namaParts[0],
                    "last_name" => end($namaParts),
                ], ["2"])) {
                    array_push($nama_fails, "UERR: " . $nama);
                }
            }
            if ($act) {
                $berhasil++;
            } else {
                array_push($nama_fails, $nama);
            }
        }
        $this->db->trans_complete();
        $this->touch_last_sync();

        return $this->output_json([
            "message" => "Sukses menarik " . $berhasil . " data GTK",
            "fails" => $nama_fails,
        ]);
    }

    public function tarikDataSiswa()
    {
        $fetched = $this->fetch_rows("/WebService/getPesertaDidik", 180);
        if (isset($fetched["error"])) {
            return $this->output_json(["error" => $fetched["error"]]);
        }
        $json = (object) ["rows" => $fetched["rows"]];

        $id_tp = $this->master->getTahunActive()->id_tp;
        $id_smt = $this->master->getSemesterActive()->id_smt;
        $nama_fails = [];
        $terdaftar = 0;

        $this->db->trans_start();
        foreach ($json->rows as $value) {
            $rombel = isset($value->nama_rombel) ? $value->nama_rombel : "";
            $kode_kelas = strtolower(preg_replace("/\s+/", "_", $rombel));
            $kelas = explode(" ", $rombel);
            $levelKelas = 10;
            if (isset($kelas[0]) && $kelas[0] === "XI") {
                $levelKelas = 11;
            }
            if (isset($kelas[0]) && $kelas[0] === "XII") {
                $levelKelas = 12;
            }
            $jurusanData = ["nama_jurusan" => "Non Jurusan", "kode_jurusan" => "NONJURUSAN", "mapel_peminatan" => ""];
            if (count($kelas) > 2) {
                $jurusanData["nama_jurusan"] = $kelas[1];
                $jurusanData["kode_jurusan"] = $kelas[1];
            }

            $rowJurusan = $this->db->select("id_jurusan")->from("master_jurusan")->where("kode_jurusan", $jurusanData["kode_jurusan"])->get()->row();
            if (!isset($rowJurusan)) {
                $this->master->create("master_jurusan", $jurusanData);
                $rowJurusan = $this->db->select("id_jurusan")->from("master_jurusan")->where("kode_jurusan", $jurusanData["kode_jurusan"])->get()->row();
            }

            $data = [
                "nisn" => isset($value->nisn) ? $value->nisn : "",
                "nis" => isset($value->nipd) ? $value->nipd : "",
                "nama" => isset($value->nama) ? $value->nama : "",
                "jenis_kelamin" => isset($value->jenis_kelamin) ? $value->jenis_kelamin : "",
                "username" => isset($value->nisn) ? $value->nisn : "",
                "password" => isset($value->nipd) ? $value->nipd : "",
                "tahun_masuk" => isset($value->tanggal_masuk_sekolah) ? explode("-", $value->tanggal_masuk_sekolah)[0] : "",
                "sekolah_asal" => isset($value->sekolah_asal) ? $value->sekolah_asal : "",
                "tempat_lahir" => isset($value->tempat_lahir) ? $value->tempat_lahir : "",
                "tanggal_lahir" => isset($value->tanggal_lahir) ? $value->tanggal_lahir : "",
                "agama" => isset($value->agama_id_str) ? $value->agama_id_str : "",
                "hp" => isset($value->nomor_telepon_seluler) ? $value->nomor_telepon_seluler : "",
                "email" => isset($value->email) ? $value->email : "",
                "nama_ayah" => isset($value->nama_ayah) ? $value->nama_ayah : "",
                "pekerjaan_ayah" => isset($value->pekerjaan_ayah_id_str) ? $value->pekerjaan_ayah_id_str : "",
                "nama_ibu" => isset($value->nama_ibu) ? $value->nama_ibu : "",
                "pekerjaan_ibu" => isset($value->pekerjaan_ibu_id_str) ? $value->pekerjaan_ibu_id_str : "",
                "nik" => isset($value->nik) ? $value->nik : "",
                "warga_negara" => "Indonesia",
                "foto" => "uploads/foto_siswa/" . (isset($value->nipd) ? $value->nipd : "") . ".jpg",
            ];

            if (!$data["nis"] || !$data["nisn"]) {
                array_push($nama_fails, "[N]: " . $data["nama"]);
                continue;
            }

            $row = $this->db->select("nama, id_siswa, username, password")->from("master_siswa")->where("nisn", $data["nisn"])->get()->row();
            if (isset($row)) {
                $update = $data;
                if (trim((string) $row->username) !== "") {
                    unset($update["username"]);
                }
                if (trim((string) $row->password) !== "") {
                    unset($update["password"]);
                }
                $tersimpan = $this->master->update("master_siswa", $update, "nisn", $data["nisn"]);
                $id_siswa = $row->id_siswa;
            } else {
                $this->db->set("uid", "UUID()", FALSE);
                $tersimpan = $this->master->create("master_siswa", $data);
                $id_siswa = $this->db->insert_id();
            }
            if (!$tersimpan || !$id_siswa) {
                array_push($nama_fails, "[C]: " . $data["nama"]);
                continue;
            }
            $terdaftar++;

            $rowKelas = $this->db->select("id_kelas, siswa_id")->from("master_kelas")->where("kode_kelas", $kode_kelas)->get()->row();
            $anggota = [];
            if ($rowKelas) {
                $current = $this->db->select("id_siswa")->from("kelas_siswa")->where("id_kelas", $rowKelas->id_kelas)->get()->result();
                foreach ($current as $s) {
                    $anggota[] = ["id" => $s->id_siswa];
                }
            }
            if (!in_array($id_siswa, array_column($anggota, "id"))) {
                $anggota[] = ["id" => $id_siswa];
            }
            $insertDataKelas = [
                "nama_kelas" => $rombel,
                "kode_kelas" => $kode_kelas,
                "jurusan_id" => isset($rowJurusan->id_jurusan) ? $rowJurusan->id_jurusan : 0,
                "id_tp" => $id_tp,
                "id_smt" => $id_smt,
                "level_id" => $levelKelas,
                "siswa_id" => $rowKelas ? $rowKelas->siswa_id : $id_siswa,
                "jumlah_siswa" => serialize($anggota),
                "guru_id" => 0,
            ];
            if ($rowKelas) {
                $this->master->update("master_kelas", $insertDataKelas, "id_kelas", $rowKelas->id_kelas);
                $id_kelas = $rowKelas->id_kelas;
            } else {
                $this->master->create("master_kelas", $insertDataKelas);
                $id_kelas = $this->db->insert_id();
            }

            $kelasSiswa = $this->db->select("id_siswa")->from("kelas_siswa")->where("id_siswa", $id_siswa)->get()->num_rows();
            $kelasSiswaData = [
                "id_kelas_siswa" => $id_tp . $id_smt . $id_siswa,
                "id_tp" => $id_tp,
                "id_smt" => $id_smt,
                "id_siswa" => $id_siswa,
                "id_kelas" => $id_kelas,
            ];
            if ($kelasSiswa > 0) {
                $this->master->update("kelas_siswa", $kelasSiswaData, "id_siswa", $id_siswa);
            } else {
                $this->master->create("kelas_siswa", $kelasSiswaData);
            }

            $bukuInduk = $this->db->select("id_siswa")->from("buku_induk")->where("id_siswa", $id_siswa)->get()->num_rows();
            if ($bukuInduk < 1) {
                $this->master->create("buku_induk", ["id_siswa" => $id_siswa]);
            }
        }
        $this->db->trans_complete();
        $this->touch_last_sync();

        return $this->output_json([
            "message" => "Telah terdaftar " . $terdaftar . " siswa dari " . count($json->rows) . " data Dapodik",
            "fails" => $nama_fails,
        ]);
    }

    private function touch_last_sync()
    {
        if (!schema_has_column("setting", "dapodik_last_sync")) {
            return;
        }
        $this->db->where("id_setting", 1);
        $this->db->update("setting", ["dapodik_last_sync" => date("Y-m-d H:i:s")]);
    }

    protected function get_dapodik_httpclient()
    {
        return new \GuzzleHttp\Client([
            "base_uri" => $this->dapodik["url"],
            "timeout" => 30,
            "connect_timeout" => 10,
            "headers" => [
                "Authorization" => "Bearer " . $this->dapodik["token"],
                "Accept" => "application/json",
            ],
            "query" => ["npsn" => $this->dapodik["npsn"]],
        ]);
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) {
            $data = json_encode($data);
        }
        $this->output->set_content_type("application/json")->set_output($data);
    }
}
