<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Dataalumni extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto RX5fZ;
        }
        if ($this->ion_auth->is_admin()) {
            goto CyNGc;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        CyNGc:
        RX5fZ:
        $this->load->library("upload");
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Kelas_model", "kelas");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Master_model", "master");
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Rapor_model", "rapor");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto FBpN0;
        }
        $data = json_encode($data);
        FBpN0:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $tahun = $this->input->get("tahun", true);
        $kelas_akhir = $this->input->get("kelas", true);
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Data Kelulusan & Alumni", "subjudul" => "Data Alumni", "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $allTp = $this->dashboard->getTahun();
        $data["tp"] = $allTp;
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $data["tahun_lulus"] = $this->master->getDistinctTahunLulus();
        $data["kelas_akhir"] = $this->master->getDistinctKelasAkhir();
        $data["tahun_selected"] = $tahun;
        $data["kelas_selected"] = $kelas_akhir;
        $level = $setting->jenjang == "1" ? "6" : ($setting->jenjang == "2" ? "9" : ($setting->jenjang == "1" ? "3" : "12"));
        $jumlah_lulus = $this->rapor->getJumlahLulus($tp->id_tp - 1, "2", $level);
        $idSearch = array_search($tp->id_tp - 1, array_column($allTp, "id_tp"));
        $tpBefore = $allTp[$idSearch]->tahun;
        $splitTahun = explode("/", $tpBefore);
        $alumnis = $this->master->getAlumniByTahun($splitTahun[1]);
        if ($jumlah_lulus > count($alumnis)) {
            $data["jumlah_lulus"] = $jumlah_lulus;
            goto ZSVWb;
        }
        $data["jumlah_lulus"] = 0;
        ZSVWb:
        if ($tahun == null) {
            $count_siswa = $this->db->count_all("master_siswa");
            $count_induk = $this->db->count_all("buku_induk");
            if (!($count_siswa > $count_induk)) {
                goto soRCw;
            }
            $uids = $this->db->select("id_siswa, uid")->from("master_siswa")->get()->result();
            foreach ($uids as $uid) {
                $check = $this->db->select("id_siswa")->from("buku_induk")->where("id_siswa", $uid->id_siswa);
                if (!($check->get()->num_rows() == 0)) {
                    goto iNhNv;
                }
                $this->db->insert("buku_induk", $uid);
                iNhNv:
            }
            soRCw:
            goto mRhoJ;
        }
        if ($tahun != null && $tahun != '') {
            $data["alumnis"] = $this->master->getAlumniByTahun($tahun, $kelas_akhir);
            goto is64q;
        }
        is64q:
        mRhoJ:
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/alumni/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function generateAlumni()
    {
        $setting = $this->dashboard->getSetting();
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $allTp = $this->dashboard->getTahun();
        $searchId = array_search("1", array_column($allTp, "active"));
        $idBefore = $allTp[$searchId - 1]->id_tp;
        $tpBefore = $allTp[$searchId - 1]->tahun;
        $splitTahun = explode("/", $tpBefore);
        $level = $setting->jenjang == "1" ? "6" : ($setting->jenjang == "2" ? "9" : ($setting->jenjang == "1" ? "3" : "12"));
        $siswas = $this->rapor->getSiswaLulus($tp->id_tp - 1, "2", $level);
        $ids = [];
        $this->db->trans_start();
        foreach ($siswas as $siswa) {
            if ($siswa->naik != null && $siswa->naik == "0") {
                goto JzjNb;
            }
            $ids[] = $siswa->id_siswa;
            $this->db->where("id_siswa", $siswa->id_siswa);
            $this->db->set("status", "2");
            $this->db->set("tahun_lulus", $splitTahun[1]);
            $this->db->set("no_ijazah", "- -");
            $this->db->set("kelas_akhir", $siswa->kelas_akhir);
            $this->db->update("buku_induk");
            JzjNb:
        }
        $this->db->trans_complete();
        $this->output_json($ids);
    }
    public function luluskan()
    {
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $posts = json_decode($this->input->post("kelas", true));
        $mode = $this->input->post("mode", true);
        $idkelases = [];
        $alumnikelas = [];
        foreach ($posts as $d) {
            $idkelases[] = $d->kelas_baru;
            $alumnikelas[$d->kelas_baru][] = ["id" => $d->id_siswa];
        }
        $idkelases = array_unique($idkelases);
        $res = [];
        $idks = [];
        foreach ($idkelases as $ik) {
            $kelas = $this->kelas->get_one($ik, $tp->id_tp - 1, "2");
            $kelas_baru = $this->kelas->getKelasByNama($kelas->nama_kelas, $tp->id_tp, $smt->id_smt);
            if ($kelas_baru == null) {
                $jumlah = serialize($alumnikelas[$ik]);
                $data = array("nama_kelas" => $kelas->nama_kelas, "kode_kelas" => $kelas->kode_kelas, "jurusan_id" => $kelas->jurusan_id, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "level_id" => $kelas->level_id, "guru_id" => $kelas->guru_id, "alumni_id" => $kelas->alumni_id, "jumlah_alumni" => $jumlah);
                $this->db->insert("master_kelas", $data);
                array_push($idks, $this->db->insert_id());
                goto l2aX_;
            }
            if ($mode == "peralumni") {
                $jmlLama = unserialize($kelas_baru->jumlah_alumni);
                foreach ($alumnikelas[$ik] as $s) {
                    foreach ($jmlLama as $lama) {
                        if (!($lama["id"] != $s["id"])) {
                            goto vjPNO;
                        }
                        array_push($jmlLama, ["id" => $s["id"]]);
                        array_push($idks, $kelas_baru->id_kelas);
                        vjPNO:
                    }
                }
                $jumlah = serialize($jmlLama);
                goto vD7QE;
            }
            $jumlah = serialize($alumnikelas[$ik]);
            array_push($idks, $kelas_baru->id_kelas);
            vD7QE:
            $data = array("nama_kelas" => $kelas->nama_kelas, "kode_kelas" => $kelas->kode_kelas, "jurusan_id" => $kelas->jurusan_id, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "level_id" => $kelas->level_id, "guru_id" => $kelas->guru_id, "alumni_id" => $kelas->alumni_id, "jumlah_alumni" => $jumlah);
            $this->db->where("id_kelas", $kelas_baru->id_kelas);
            $this->db->update("master_kelas", $data);
            l2aX_:
            foreach ($idks as $idk) {
                foreach ($alumnikelas[$ik] as $s) {
                    $insert = ["id_kelas_alumni" => $tp->id_tp . $smt->id_smt . $s["id"], "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "id_kelas" => $idk, "id_siswa" => $s["id"]];
                    $res[] = $this->db->replace("kelas_alumni", $insert);
                }
            }
        }
        $data["res"] = $alumnikelas;
        $this->output_json($data);
    }
    public function detail($id)
    {
        $alumni = $this->master->getAlumniById($id);
        $inputData = [["label" => "Nama Lengkap", "name" => "nama", "value" => $alumni->nama, "icon" => "far fa-user", "class" => '', "type" => "text"], ["label" => "NIS", "name" => "nis", "value" => $alumni->nis, "icon" => "far fa-id-card", "class" => '', "type" => "number"], ["name" => "nisn", "label" => "NISN", "value" => $alumni->nisn, "icon" => "far fa-id-card", "class" => '', "type" => "text"], ["label" => "Jenis Kelamin", "name" => "jenis_kelamin", "value" => $alumni->jenis_kelamin, "icon" => "fas fa-venus-mars", "class" => '', "type" => "text"], ["name" => "kelas_awal", "label" => "Diterima di kelas", "value" => $alumni->kelas_awal, "icon" => "fas fa-graduation-cap", "class" => '', "type" => "text"], ["name" => "tahun_masuk", "label" => "Tgl diterima", "value" => $alumni->tahun_masuk, "icon" => "tahun far fa-calendar-alt", "class" => "tahun", "type" => "text"]];
        $inputBio = [["name" => "tempat_lahir", "label" => "Tempat Lahir", "value" => $alumni->tempat_lahir, "icon" => "far fa-map", "class" => '', "type" => "text"], ["name" => "tanggal_lahir", "label" => "Tanggal Lahir", "value" => $alumni->tanggal_lahir, "icon" => "far fa-calendar", "class" => "tahun", "type" => "text"], ["class" => '', "name" => "agama", "label" => "Agama", "value" => $alumni->agama, "icon" => "far fa-calendar", "type" => "text"], ["class" => '', "name" => "alamat", "label" => "Alamat", "value" => $alumni->alamat, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "rt", "label" => "Rt", "value" => $alumni->rt, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "rw", "label" => "Rw", "value" => $alumni->rw, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kelurahan", "label" => "Kelurahan/Desa", "value" => $alumni->kelurahan, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kecamatan", "label" => "Kecamatan", "value" => $alumni->kecamatan, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kabupaten", "label" => "Kabupaten/Kota", "value" => $alumni->kabupaten, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kode_pos", "label" => "Kode Pos", "value" => $alumni->kode_pos, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "hp", "label" => "Hp", "value" => $alumni->hp, "icon" => "far fa-user", "type" => "text"]];
        $inputOrtu = [["name" => "nama_ayah", "label" => "Nama Ayah", "value" => $alumni->nama_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "pendidikan_ayah", "label" => "Pendidikan Ayah", "value" => $alumni->pendidikan_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "pekerjaan_ayah", "label" => "Pekerjaan Ayah", "value" => $alumni->pekerjaan_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "nohp_ayah", "label" => "No. HP Ayah", "value" => $alumni->nohp_ayah, "icon" => "far fa-user", "type" => "number"], ["name" => "alamat_ayah", "label" => "Alamat Ayah", "value" => $alumni->alamat_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "nama_ibu", "label" => "Nama Ibu", "value" => $alumni->nama_ibu, "icon" => "far fa-user", "type" => "text"], ["name" => "pendidikan_ibu", "label" => "Pendidikan Ibu", "value" => $alumni->pendidikan_ibu, "icon" => "far fa-user", "type" => "text"], ["name" => "pekerjaan_ibu", "label" => "Pekerjaan Ibu", "value" => $alumni->pekerjaan_ibu, "icon" => "far fa-user", "type" => "text"], ["name" => "nohp_ibu", "label" => "No. HP Ibu", "value" => $alumni->nohp_ibu, "icon" => "far fa-user", "type" => "number"], ["name" => "alamat_ibu", "label" => "Alamat Ibu", "value" => $alumni->alamat_ibu, "icon" => "far fa-user", "type" => "text"]];
        $inputWali = [["name" => "nama_wali", "label" => "Nama Wali", "value" => $alumni->nama_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "pendidikan_wali", "label" => "Pendidikan Wali", "value" => $alumni->pendidikan_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "pekerjaan_wali", "label" => "Pekerjaan Wali", "value" => $alumni->pekerjaan_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "alamat_wali", "label" => "Alamat Wali", "value" => $alumni->alamat_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "nohp_wali", "label" => "No. HP Wali", "value" => $alumni->nohp_wali, "icon" => "far fa-user", "type" => "number"]];
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Alumni", "subjudul" => "Edit Data Alumni", "alumni" => $alumni, "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data["input_data"] = json_decode(json_encode($inputData), FALSE);
        $data["input_bio"] = json_decode(json_encode($inputBio), FALSE);
        $data["input_ortu"] = json_decode(json_encode($inputOrtu), FALSE);
        $data["input_wali"] = json_decode(json_encode($inputWali), FALSE);
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/alumni/edit");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function add()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Alumni", "subjudul" => "Tambah Data Alumni", "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $data["tipe"] = "add";
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/alumni/add");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function create()
    {
        $nis = $this->input->post("nis", true);
        $nisn = $this->input->post("nisn", true);
        $u_nis = "|is_unique[master_siswa.nis]";
        $u_nisn = "|is_unique[master_siswa.nisn]";
        $this->form_validation->set_rules("nis", "NIS", "required|numeric|trim|min_length[6]|max_length[30]|is_unique[master_siswa.nis]");
        $this->form_validation->set_rules("nisn", "NISN", "required|numeric|trim|min_length[6]|max_length[20]|is_unique[master_siswa.nisn]");
        if ($this->form_validation->run() == FALSE) {
            $data["insert"] = false;
            $data["text"] = "Data Sudah ada, Pastikan NIS, NISN dan Username belum digunakan alumni lain";
            goto DenLu;
        }
        $insert = ["nama" => $this->input->post("nama_alumni", true), "nis" => $nis, "nisn" => $nisn, "jenis_kelamin" => $this->input->post("jenis_kelamin", true), "foto" => "uploads/foto_siswa/" . $nis . "jpg"];
        $this->db->set("uid", "UUID()", FALSE);
        $this->db->insert("master_siswa", $insert);
        $last_id = $this->db->insert_id();
        $uid = $this->db->select("uid")->from("master_siswa")->where("id_siswa", $last_id)->get()->row();
        $induk = ["id_siswa" => $last_id, "uid" => $uid->uid, "kelas_akhir" => $this->input->post("kelas_akhir", true), "tahun_lulus" => $this->input->post("tahun_lulus", true), "no_ijazah" => $this->input->post("no_ijazah", true), "status" => 2];
        $data["insert"] = $this->db->insert("buku_induk", $induk);
        $data["text"] = "Alumni berhasil ditambahkan";
        DenLu:
        $this->output_json($data);
    }
    public function edit()
    {
        $id = $this->input->get("id", true);
        $alumni = $this->master->getAlumniById($id);
        $inputData = [["label" => "Nama Lengkap", "name" => "nama", "value" => $alumni->nama, "icon" => "far fa-user", "class" => '', "type" => "text"], ["label" => "NIS", "name" => "nis", "value" => $alumni->nis, "icon" => "far fa-id-card", "class" => '', "type" => "number"], ["name" => "nisn", "label" => "NISN", "value" => $alumni->nisn, "icon" => "far fa-id-card", "class" => '', "type" => "text"], ["label" => "Jenis Kelamin", "name" => "jenis_kelamin", "value" => $alumni->jenis_kelamin, "icon" => "fas fa-venus-mars", "class" => '', "type" => "text"], ["name" => "kelas_awal", "label" => "Diterima di kelas", "value" => $alumni->kelas_awal, "icon" => "fas fa-graduation-cap", "class" => '', "type" => "text"], ["name" => "tahun_masuk", "label" => "Tgl diterima", "value" => $alumni->tahun_masuk, "icon" => "tahun far fa-calendar-alt", "class" => "tahun", "type" => "text"]];
        $inputBio = [["name" => "tempat_lahir", "label" => "Tempat Lahir", "value" => $alumni->tempat_lahir, "icon" => "far fa-map", "class" => '', "type" => "text"], ["name" => "tanggal_lahir", "label" => "Tanggal Lahir", "value" => $alumni->tanggal_lahir, "icon" => "far fa-calendar", "class" => "tahun", "type" => "text"], ["class" => '', "name" => "agama", "label" => "Agama", "value" => $alumni->agama, "icon" => "far fa-calendar", "type" => "text"], ["class" => '', "name" => "alamat", "label" => "Alamat", "value" => $alumni->alamat, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "rt", "label" => "Rt", "value" => $alumni->rt, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "rw", "label" => "Rw", "value" => $alumni->rw, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kelurahan", "label" => "Kelurahan/Desa", "value" => $alumni->kelurahan, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kecamatan", "label" => "Kecamatan", "value" => $alumni->kecamatan, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kabupaten", "label" => "Kabupaten/Kota", "value" => $alumni->kabupaten, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "kode_pos", "label" => "Kode Pos", "value" => $alumni->kode_pos, "icon" => "far fa-user", "type" => "text"], ["class" => '', "name" => "hp", "label" => "Hp", "value" => $alumni->hp, "icon" => "far fa-user", "type" => "text"]];
        $inputOrtu = [["name" => "nama_ayah", "label" => "Nama Ayah", "value" => $alumni->nama_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "pendidikan_ayah", "label" => "Pendidikan Ayah", "value" => $alumni->pendidikan_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "pekerjaan_ayah", "label" => "Pekerjaan Ayah", "value" => $alumni->pekerjaan_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "nohp_ayah", "label" => "No. HP Ayah", "value" => $alumni->nohp_ayah, "icon" => "far fa-user", "type" => "number"], ["name" => "alamat_ayah", "label" => "Alamat Ayah", "value" => $alumni->alamat_ayah, "icon" => "far fa-user", "type" => "text"], ["name" => "nama_ibu", "label" => "Nama Ibu", "value" => $alumni->nama_ibu, "icon" => "far fa-user", "type" => "text"], ["name" => "pendidikan_ibu", "label" => "Pendidikan Ibu", "value" => $alumni->pendidikan_ibu, "icon" => "far fa-user", "type" => "text"], ["name" => "pekerjaan_ibu", "label" => "Pekerjaan Ibu", "value" => $alumni->pekerjaan_ibu, "icon" => "far fa-user", "type" => "text"], ["name" => "nohp_ibu", "label" => "No. HP Ibu", "value" => $alumni->nohp_ibu, "icon" => "far fa-user", "type" => "number"], ["name" => "alamat_ibu", "label" => "Alamat Ibu", "value" => $alumni->alamat_ibu, "icon" => "far fa-user", "type" => "text"]];
        $inputWali = [["name" => "nama_wali", "label" => "Nama Wali", "value" => $alumni->nama_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "pendidikan_wali", "label" => "Pendidikan Wali", "value" => $alumni->pendidikan_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "pekerjaan_wali", "label" => "Pekerjaan Wali", "value" => $alumni->pekerjaan_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "alamat_wali", "label" => "Alamat Wali", "value" => $alumni->alamat_wali, "icon" => "far fa-user", "type" => "text"], ["name" => "nohp_wali", "label" => "No. HP Wali", "value" => $alumni->nohp_wali, "icon" => "far fa-user", "type" => "number"]];
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Alumni", "subjudul" => "Edit Data Alumni", "alumni" => $alumni, "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data["input_data"] = json_decode(json_encode($inputData), FALSE);
        $data["input_bio"] = json_decode(json_encode($inputBio), FALSE);
        $data["input_ortu"] = json_decode(json_encode($inputOrtu), FALSE);
        $data["input_wali"] = json_decode(json_encode($inputWali), FALSE);
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/alumni/edit");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function updateData()
    {
        $id_siswa = $this->input->post("id_siswa", true);
        $nis = $this->input->post("nis", true);
        $nisn = $this->input->post("nisn", true);
        $alumni = $this->master->getAlumniById($id_siswa);
        $u_nis = $alumni->nis === $nis ? '' : "|is_unique[mater_alumni.nis]";
        $u_nisn = $alumni->nisn === $nisn ? '' : "|is_unique[mater_alumni.nisn]";
        $this->form_validation->set_rules("nis", "NIS", "required|numeric|trim|min_length[6]|max_length[30]" . $u_nis);
        $this->form_validation->set_rules("nisn", "NISN", "required|numeric|trim|min_length[6]|max_length[20]" . $u_nisn);
        if ($this->form_validation->run() == FALSE) {
            $data["insert"] = false;
            $data["text"] = "Data Sudah ada, Pastikan NIS, dan NISN belum digunakan alumni lain";
            goto Hs6V_;
        }
        $input = ["nisn" => $this->input->post("nisn", true), "nis" => $this->input->post("nis", true), "nama" => $this->input->post("nama", true), "jenis_kelamin" => $this->input->post("jenis_kelamin", true), "tempat_lahir" => $this->input->post("tempat_lahir", true), "tanggal_lahir" => $this->input->post("tanggal_lahir", true), "agama" => $this->input->post("agama", true), "status_keluarga" => $this->input->post("status_keluarga", true), "anak_ke" => $this->input->post("anak_ke", true), "alamat" => $this->input->post("alamat", true), "rt" => $this->input->post("rt", true), "rw" => $this->input->post("rw", true), "kelurahan" => $this->input->post("kelurahan", true), "kecamatan" => $this->input->post("kecamatan", true), "kabupaten" => $this->input->post("kabupaten", true), "provinsi" => $this->input->post("provinsi", true), "kode_pos" => $this->input->post("kode_pos", true), "hp" => $this->input->post("hp", true), "nama_ayah" => $this->input->post("nama_ayah", true), "nohp_ayah" => $this->input->post("nohp_ayah", true), "pendidikan_ayah" => $this->input->post("pendidikan_ayah", true), "pekerjaan_ayah" => $this->input->post("pekerjaan_ayah", true), "alamat_ayah" => $this->input->post("alamat_ayah", true), "nama_ibu" => $this->input->post("nama_ibu", true), "nohp_ibu" => $this->input->post("nohp_ibu", true), "pendidikan_ibu" => $this->input->post("pendidikan_ibu", true), "pekerjaan_ibu" => $this->input->post("pekerjaan_ibu", true), "alamat_ibu" => $this->input->post("alamat_ibu", true), "nama_wali" => $this->input->post("nama_wali", true), "pendidikan_wali" => $this->input->post("pendidikan_wali", true), "pekerjaan_wali" => $this->input->post("pekerjaan_wali", true), "nohp_wali" => $this->input->post("nohp_wali", true), "alamat_wali" => $this->input->post("alamat_wali", true), "tahun_masuk" => $this->input->post("tahun_masuk", true), "kelas_awal" => $this->input->post("kelas_awal", true), "tgl_lahir_ayah" => $this->input->post("tgl_lahir_ayah", true), "tgl_lahir_ibu" => $this->input->post("tgl_lahir_ibu", true), "tgl_lahir_wali" => $this->input->post("tgl_lahir_wali", true), "sekolah_asal" => $this->input->post("sekolah_asal", true), "foto" => "uploads/foto_siswa/" . $nis . ".jpg"];
        $action = $this->master->update("master_siswa", $input, "id_siswa", $id_siswa);
        $data["insert"] = $input;
        $data["text"] = "Alumni berhasil diperbaharui";
        Hs6V_:
        $this->output_json($data);
    }
    function uploadFile($id_siswa)
    {
        $alumni = $this->master->getAlumniById($id_siswa);
        if (isset($_FILES["foto"]["name"])) {
            $config["upload_path"] = "./uploads/foto_siswa/";
            $config["allowed_types"] = "gif|jpg|png|jpeg|JPEG|JPG|PNG|GIF";
            $config["overwrite"] = true;
            $config["file_name"] = $alumni->nis;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload("foto")) {
                $data["status"] = false;
                $data["src"] = $this->upload->display_errors();
                goto EgmXK;
            }
            $result = $this->upload->data();
            $data["src"] = base_url() . "uploads/foto_siswa/" . $result["file_name"];
            $data["filename"] = pathinfo($result["file_name"], PATHINFO_FILENAME);
            $data["status"] = true;
            $this->db->set("foto", "uploads/foto_siswa/" . $result["file_name"]);
            $this->db->where("id_siswa", $id_siswa);
            $this->db->update("master_siswa");
            EgmXK:
            $data["type"] = $_FILES["foto"]["type"];
            $data["size"] = $_FILES["foto"]["size"];
            goto kC6TM;
        }
        $data["src"] = '';
        kC6TM:
        $this->output_json($data);
    }
    function deleteFoto()
    {
        $src = $this->input->post("src");
        $file_name = str_replace(base_url(), '', $src);
        unlink($file_name);
        echo "File Delete Successfully";
    }
    public function delete()
    {
        $chk = $this->input->post("checked", true);
        if (!$chk) {
            $this->output_json(["status" => false]);
            goto MDDou;
        }
        if (!$this->master->delete("master_siswa", $chk, "id_siswa")) {
            goto vbbed;
        }
        $this->output_json(["status" => true, "total" => count($chk)]);
        vbbed:
        MDDou:
    }
    public function previewExcel()
    {
        $config["upload_path"] = "./uploads/import/";
        $config["allowed_types"] = "xls|xlsx|csv";
        $config["max_size"] = 2048;
        $config["encrypt_name"] = true;
        $this->upload->initialize($config);
        if (!$this->upload->do_upload("upload_file")) {
            $error = $this->upload->display_errors();
            echo $error;
            die;
        }
        $file = $this->upload->data("full_path");
        $ext = $this->upload->data("file_ext");
        switch ($ext) {
            case ".xlsx":
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                goto G6JFj;
            case ".xls":
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                goto G6JFj;
            case ".csv":
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                goto G6JFj;
            default:
                echo "unknown file ext";
                die;
        }
        G6JFj:
        $spreadsheet = $reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $data = [];
        $i = 1;
        BL3JG:
        if (!($i < count($sheetData))) {
            unlink($file);
            echo json_encode($data);
            // [PHPDeobfuscator] Implied return
            return;
        }
        if (!($sheetData[$i][0] != null)) {
            goto HM4ws;
        }
        $data[] = ["nisn" => str_replace("'", '', $sheetData[$i][1]), "nis" => str_replace("'", '', $sheetData[$i][2]), "nama" => $sheetData[$i][3], "jenis_kelamin" => $sheetData[$i][4], "username" => $sheetData[$i][5], "password" => $sheetData[$i][6], "tempat_lahir" => $sheetData[$i][10], "tanggal_lahir" => $sheetData[$i][11], "agama" => $sheetData[$i][12], "status_keluarga" => $sheetData[$i][16], "anak_ke" => $sheetData[$i][15], "alamat" => $sheetData[$i][17], "rt" => $sheetData[$i][18], "rw" => $sheetData[$i][19], "kelurahan" => $sheetData[$i][20], "kecamatan" => $sheetData[$i][21], "kabupaten" => $sheetData[$i][22], "provinsi" => $sheetData[$i][23], "kode_pos" => $sheetData[$i][24], "hp" => str_replace("'", '', $sheetData[$i][13]), "nama_ayah" => $sheetData[$i][25], "nohp_ayah" => str_replace("'", '', $sheetData[$i][29]), "pendidikan_ayah" => $sheetData[$i][27], "pekerjaan_ayah" => $sheetData[$i][28], "alamat_ayah" => $sheetData[$i][30], "nama_ibu" => $sheetData[$i][31], "nohp_ibu" => str_replace("'", '', $sheetData[$i][35]), "pendidikan_ibu" => $sheetData[$i][33], "pekerjaan_ibu" => $sheetData[$i][34], "alamat_ibu" => $sheetData[$i][36], "nama_wali" => $sheetData[$i][37], "pendidikan_wali" => $sheetData[$i][39], "pekerjaan_wali" => $sheetData[$i][40], "nohp_wali" => str_replace("'", '', $sheetData[$i][41]), "alamat_wali" => $sheetData[$i][42], "tahun_masuk" => $sheetData[$i][8], "kelas_awal" => $sheetData[$i][7], "tgl_lahir_ayah" => $sheetData[$i][26], "tgl_lahir_ibu" => $sheetData[$i][32], "tgl_lahir_wali" => $sheetData[$i][38], "sekolah_asal" => $sheetData[$i][9], "id_siswa" => isset($sheetData[$i][43]) ? $sheetData[$i][43] : ''];
        HM4ws:
        $i++;
        goto BL3JG;
    }
    public function do_import()
    {
        $input = json_decode($this->input->post("alumni", true));
        $this->db->trans_start();
        foreach ($input as $key1 => $val1) {
            $data = [];
            foreach (((array) $input)[$key1] as $key => $val) {
                $data[$key] = $val;
            }
            $data["foto"] = "uploads/foto_siswa/" . $data["nis"] . ".jpg";
            $save = $this->db->insert("master_siswa", $data);
        }
        $this->db->trans_complete();
        $this->output->set_content_type("application/json")->set_output($save);
    }
    public function editKelulusan()
    {
        $id_siswa = $this->input->post("id_siswa", true);
        $thn = $this->input->post("tahun_lulus", true);
        $no_ijazah = $this->input->post("no_ijazah", true);
        $kelas_akhir = $this->input->post("kelas_akhir", true);
        $this->db->set("kelas_akhir", $kelas_akhir);
        $this->db->set("tahun_lulus", $thn);
        $this->db->set("no_ijazah", $no_ijazah);
        $this->db->where("id_siswa", $id_siswa);
        $status = $this->db->update("master_siswa");
        $data["status"] = $status;
        $this->output_json($data);
    }
}
