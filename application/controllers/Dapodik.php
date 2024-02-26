<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");

class Dapodik extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto OdHIi;
        }
        if ($this->ion_auth->is_admin()) {
            goto YNpDy;
        }
        show_error("Hanya Admin yang boleh mengakses halaman ini", 403, "Akses dilarang");
        YNpDy:
        OdHIi:
        $this->load->library("upload");
        $this->load->model("Settings_model", "settings");
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->helper("directory");
    }

    public function tarikDataGtk() {
        $setting = $this->dashboard->getSetting();
        $npsn = $setting->npsn;

        $httpClient = $this->get_dapodik_httpclient(
            $this->config->item('DAPODIK_WEBSERVICE_URL'),
            $this->config->item('DAPODIK_WEBSERVICE_TOKEN'),
            $npsn,
        );

        $response = $httpClient->get('/WebService/getGtk');
        if (!$response) {
            $this->output_json([
                'error' => 'NPSN kemungkinan salah atau ada kesalahan yang tidak terduga',
            ]);
            return;
        }

        $json = json_decode((string) $response->getBody());
        $nama_fails = [];

        $this->db->trans_start();
        foreach($json->rows as $value) {
            $row = $this->db->where('nip', $value->nip);

            $data = [
                'nip' => $value->nip,
                'nama_guru' => $value->nama,
                'username' => $value->nip,
                'password' => '123456' . $value->tahun_ajaran_id,
                "foto" => "uploads/profiles/" . $value->nip . ".jpg",
                "jenis_kelamin" => $value->jenis_kelamin,
                "agama" => $value->agama_id_str,
            ];

            $action = $this->db->update('master_guru', $data);
            if (!$action) {
                $action = $this->db->replace('master_guru', $data);
                if (!$action) {
                    array_push($nama_fails, $value->nama);
                }
            }
        }
        $this->db->trans_complete();

        $this->output_json([
            'message' => 'Sukses menarik ' . strval(count($json->rows) - count($nama_fails)) . ' data GTK',
            'fails' => $nama_fails,
        ]);
        return;
    }

    public function tarikDataSiswa() {
        $setting = $this->dashboard->getSetting();
        $npsn = $setting->npsn;

        $httpClient = $this->get_dapodik_httpclient(
            $this->config->item('DAPODIK_WEBSERVICE_URL'),
            $this->config->item('DAPODIK_WEBSERVICE_TOKEN'),
            $npsn,
        );

        $response = $httpClient->get('/WebService/getPesertaDidik');

        if (!$response) {
            $this->output_json([
                'error' => 'NPSN kemungkinan salah atau ada kesalahan yang tidak terduga',
            ]);
            return;
        }

        $json = json_decode((string) $response->getBody());
        $nama_fails = [];

        $this->db->trans_start();
        foreach ($json->rows as $index => $value) {
            $data = [
                'nisn' => $value->nisn,
                'nis' => $value->nipd,
                'nama' => $value->nama,
                'jenis_kelamin' => $value->jenis_kelamin,
                'username' => $value->nisn,
                'password' => $value->nipd,
                'tahun_masuk' => explode('-', $value->tanggal_masuk_sekolah)[0],
                'sekolah_asal' => $value->sekolah_asal,
                'tempat_lahir' => $value->tempat_lahir,
                'tanggal_lahir' => $value->tanggal_lahir,
                'agama' => $value->agama_id_str,
                'hp' => $value->nomor_telepon_seluler,
                'email' => $value->email,
                'nama_ayah' => $value->nama_ayah,
                'pekerjaan_ayah' => $value->pekerjaan_ayah_id_str,
                'nama_ibu' => $value->nama_ibu,
                'pekerjaan_ibu' => $value->pekerjaan_ibu_id_str,
                'nik' => $value->nik,
                'warga_negara' => 'Indonesia',
                'foto' => "uploads/foto_siswa/" . $value->nipd . ".jpg",
            ];

            $levelKelas = 10;
            $kelas = explode(' ', $value->nama_rombel);
            if (strcmp($kelas[0], 'X') == 0) {
                $levelKelas = 10;
            }

            if (strcmp($kelas[0], 'XI') == 0) {
                $levelKelas = 11;
            }

            if (strcmp($kelas[0], 'XII')) {
                $levelKelas = 12;
            }


            $jurusanData = [
                'nama_jurusan' => $kelas[1],
                'kode_jurusan' => $kelas[1],
                'mapel_peminatan' => '',
            ];

            if (count($kelas) == 2) {
                $jurusanData['nama_jurusan'] = 'Non Jurusan';
                $jurusanData['kode_jurusan'] = 'non-jurusan';
            }

            $actionJurusan = $this->db->update('master_jurusan', $data);
            if (!$actionJurusan) {
                $actionJurusan = $this->db->insert('master_jurusan', $data);
            }

            if (!$data['nik'] || !$data['nis'] || !$data['nisn']) {
                array_push($nama_fails, $data['nama']);
            } else {
                $action = $this->db->update("master_siswa", $data);
                if (!$action) {
                    $this->db->set("uid", "UUID()", FALSE);
                    $this->db->replace('master_siswa', $data);
                }
            }
        }
        $this->db->trans_complete();

        $uids = $this->db->select("id_siswa, uid")->from("master_siswa")->get()->result();
        foreach ($uids as $uid) {
            $check = $this->db->select("id_siswa")->from("buku_induk")->where("id_siswa", $uid->id_siswa);
            if (!($check->get()->num_rows() == 0)) {
                $this->output_json([
                    'message' => 'Terupdate ' . strval(count($uids)) . ' data',
                    'fails' => $nama_fails,
                ]);
                return;
            }
            $this->db->insert("buku_induk", $uid);
        }

        $this->output_json([
            'message' => 'Telah terdaftar ' . strval(count($uids)) . ' siswa, dari perolehan ' . strval(count($json->results) . ' siswa'),
            'fails' => $nama_fails,
        ]);
    }

    public function index() {
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = [
            'judul' => 'DAPODIK',
            'subjudul' => 'TARIK DATA DAPODIK',
            'user' => $user,
            'setting' => $setting,
            'profile' => $this->dashboard->getProfileAdmin($user->id),
        ];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;

        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view('setting/dapodik',$data);
        $this->load->view("_templates/dashboard/_footer");
    }

    protected function get_dapodik_httpclient(string $service, string $token, string $npsn) {
        $httpClient = new \GuzzleHttp\Client([
            'base_uri' => $service,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'query' => [
                'npsn' => $npsn,
            ],
        ]);
    
        return $httpClient;
    }

    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto AELLo;
        }
        $data = json_encode($data);
        AELLo:
        $this->output->set_content_type("application/json")->set_output($data);
    }
}