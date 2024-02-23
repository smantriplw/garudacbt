<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtcetak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto INdvn;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto Tbe3A;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        Tbe3A:
        INdvn:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->library("upload");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto mySR6;
        }
        $data = json_encode($data);
        mySR6:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Cetak Data Penilaian", "subjudul" => "Cetak", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $tp = $this->master->getTahunActive();
        $smt = $this->master->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["kop"] = $this->cbt->getSettingKopAbsensi();
        if ($this->ion_auth->is_admin()) {
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/cetak/data");
            $this->load->view("_templates/dashboard/_footer");
            goto bdPEc;
        }
        $data["guru"] = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $pengawas = $this->cbt->getPengawasHariIni(date("Y-m-d"));
        $data["pengawas"] = $pengawas;
        $ids_pengawas = [];
        foreach ($pengawas as $pws) {
            $ids = explode(",", $pws->id_guru);
            foreach ($ids as $id) {
                if (!(!in_array($id, $ids_pengawas) && $id != '')) {
                    goto B3xD1;
                }
                $ids_pengawas[] = $id;
                B3xD1:
            }
        }
        $data["ids_pengawas"] = $ids_pengawas;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("cbt/cetak/data");
        $this->load->view("members/guru/templates/footer");
        bdPEc:
    }
    public function kartuPeserta()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Rapor_model", "rapor");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Cetak Kartu Peserta", "subjudul" => "Cetak", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["kartu"] = $this->cbt->getSettingKartu();
        $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["ruang"] = $this->dropdown->getAllRuang();
        $data["setting_rapor"] = $this->rapor->getRaporSetting($tp->id_tp, $smt->id_smt);
        if ($this->ion_auth->is_admin()) {
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/cetak/kartu");
            $this->load->view("_templates/dashboard/_footer");
            goto fPEPp;
        }
        $data["guru"] = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("cbt/cetak/kartu");
        $this->load->view("members/guru/templates/footer");
        fPEPp:
    }
    function uploadFile($logo)
    {
        if (isset($_FILES["logo"]["name"])) {
            $config["upload_path"] = "./uploads/settings/";
            $config["allowed_types"] = "gif|jpg|png|jpeg|JPEG|JPG|PNG|GIF";
            $config["overwrite"] = true;
            $config["file_name"] = $logo;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload("logo")) {
                $data["status"] = false;
                $data["src"] = $this->upload->display_errors();
                goto BMY3l;
            }
            $result = $this->upload->data();
            $data["src"] = base_url() . "uploads/settings/" . $result["file_name"];
            $data["filename"] = pathinfo($result["file_name"], PATHINFO_FILENAME);
            $data["status"] = true;
            BMY3l:
            $data["type"] = $_FILES["logo"]["type"];
            $data["size"] = $_FILES["logo"]["size"];
            goto tmrs8;
        }
        $data["src"] = '';
        tmrs8:
        $this->output_json($data);
    }
    function deleteFile()
    {
        $src = $this->input->post("src");
        $file_name = str_replace(base_url(), '', $src);
        if (!unlink($file_name)) {
            goto xj3Kq;
        }
        echo "File Delete Successfully";
        xj3Kq:
    }
    public function saveKartu()
    {
        $header_1 = $this->input->post("header_1", true);
        $header_2 = $this->input->post("header_2", true);
        $header_3 = $this->input->post("header_3", true);
        $header_4 = $this->input->post("header_4", true);
        $tanggal = $this->input->post("tanggal", true);
        $insert = ["id_set_kartu" => 123456, "header_1" => $header_1, "header_2" => $header_2, "header_3" => $header_3, "header_4" => $header_4, "tanggal" => $tanggal];
        $update = $this->db->replace("cbt_kop_kartu", $insert);
        $this->output_json($update);
    }
    public function getSiswaKelas()
    {
        $this->load->model("Master_model", "master");
        $this->load->model("Kelas_model", "kelas");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $sesi = $this->input->get("sesi");
        $jadwal = $this->input->get("jadwal");
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $kelas = $this->input->get("kelas");
        if ($kelas == "all") {
            $ikelas = $this->kelas->getIdKelas($tp->id_tp, $smt->id_smt);
            $kelas = $ikelas;
            goto oC_sP;
        }
        $ikelas = $this->master->getKelasById($kelas);
        oC_sP:
        $s = !$sesi ? null : $sesi;
        $isesi = null;
        if (!($s != null)) {
            goto zMDou;
        }
        $isesi = $this->cbt->getSesiById($s);
        zMDou:
        $ijadwal = null;
        $pengawas = [];
        if (!($jadwal != null && $jadwal != "null")) {
            goto AqilZ;
        }
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $pengawass = $this->cbt->getPengawasByJadwal($tp->id_tp, $smt->id_smt, $jadwal, $sesi);
        $pengawas = [];
        foreach ($pengawass as $p) {
            if (!(count(explode(",", $p->id_guru)) > 0)) {
                goto ME_8T;
            }
            array_push($pengawas, $this->master->getGuruByArrId(explode(",", $p->id_guru)));
            ME_8T:
        }
        $ijadwal = $this->cbt->getJadwalById($jadwal, $s);
        AqilZ:
        $data["siswa"] = [];
        $siswas = $this->cbt->getRuangSiswaByKelas($tp->id_tp, $smt->id_smt, $kelas, $s);
        foreach ($siswas as $siswa) {
            array_push($data["siswa"], $siswa);
        }
        $data["info"] = ["kelas" => $ikelas, "sesi" => $isesi, "jadwal" => $ijadwal, "pengawas" => $pengawas];
        $this->output_json($data);
    }
    public function getSiswaRuang()
    {
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $ruang = $this->input->get("ruang");
        $sesi = $this->input->get("sesi");
        $jadwal = $this->input->get("jadwal");
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $iruang = $this->cbt->getRuangById($ruang);
        $s = $sesi == "null" ? null : $sesi;
        $isesi = null;
        if (!($s != null)) {
            goto wnogZ;
        }
        $isesi = $this->cbt->getSesiById($s);
        wnogZ:
        $ijadwal = null;
        if (!($jadwal != null && $jadwal != "null")) {
            goto pv4jg;
        }
        $ijadwal = $this->cbt->getJadwalById($jadwal, $s);
        pv4jg:
        $pengawass = $this->cbt->getPengawas($tp->id_tp . $smt->id_smt . $jadwal . $ruang . $sesi);
        $pengawas = [];
        if (!($pengawass != null && count(explode(",", $pengawass->id_guru)) > 0)) {
            goto mPpYw;
        }
        $pengawas = $this->master->getGuruByArrId(explode(",", $pengawass->id_guru));
        mPpYw:
        $data["siswa"] = $this->cbt->getSiswaByRuang($tp->id_tp, $smt->id_smt, $ruang, $s);
        $data["info"] = ["ruang" => $iruang, "sesi" => $isesi, "jadwal" => $ijadwal, "pengawas" => $pengawas];
        $this->output_json($data);
    }
    public function saveKop()
    {
        $header_1 = $this->input->post("header_1", true);
        $header_2 = $this->input->post("header_2", true);
        $header_3 = $this->input->post("header_3", true);
        $header_4 = $this->input->post("header_4", true);
        $proktor = $this->input->post("proktor", true);
        $pengawas_1 = $this->input->post("pengawas_1", true);
        $pengawas_2 = $this->input->post("pengawas_2", true);
        $insert = ["id_kop" => 123456, "header_1" => $header_1, "header_2" => $header_2, "header_3" => $header_3, "header_4" => $header_4, "proktor" => $proktor, "pengawas_1" => $pengawas_1, "pengawas_2" => $pengawas_2];
        $update = $this->db->replace("cbt_kop_absensi", $insert);
        $this->output_json($update);
    }
    public function absenPeserta()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Cetak Daftar Kehadiran", "subjudul" => "Cetak", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["jadwal"] = $this->dropdown->getAllJadwal($tp->id_tp, $smt->id_smt);
        $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["ruang"] = $this->dropdown->getAllRuang();
        $data["sesi"] = $this->dropdown->getAllSesi();
        $data["kop"] = $this->cbt->getSettingKopAbsensi();
        if ($this->ion_auth->is_admin()) {
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/cetak/absen");
            $this->load->view("_templates/dashboard/_footer");
            goto m6uLG;
        }
        $data["guru"] = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("cbt/cetak/absen");
        $this->load->view("members/guru/templates/footer");
        m6uLG:
    }
    public function beritaAcara()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Cetak Berita Acara", "subjudul" => "Cetak", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["jadwal"] = $this->dropdown->getAllJadwal($tp->id_tp, $smt->id_smt);
        $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["ruang"] = $this->dropdown->getAllRuang();
        $data["sesi"] = $this->dropdown->getAllSesi();
        $data["kop"] = $this->cbt->getSettingKopBeritaAcara();
        if ($this->ion_auth->is_admin()) {
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/cetak/beritaacara");
            $this->load->view("_templates/dashboard/_footer");
            goto w3pVg;
        }
        $data["guru"] = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("cbt/cetak/beritaacara");
        $this->load->view("members/guru/templates/footer");
        w3pVg:
    }
    public function saveKopBerita()
    {
        $header_1 = $this->input->post("header_1", true);
        $header_2 = $this->input->post("header_2", true);
        $header_3 = $this->input->post("header_3", true);
        $header_4 = $this->input->post("header_4", true);
        $insert = ["id_kop" => 123456, "header_1" => $header_1, "header_2" => $header_2, "header_3" => $header_3, "header_4" => $header_4];
        $update = $this->db->replace("cbt_kop_berita", $insert);
        $this->output_json($update);
    }
    public function pesertaUjian($mode = null)
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Cetak Daftar Peserta", "subjudul" => "Cetak", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["kelass"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["ruangs"] = $this->dropdown->getAllRuang();
        $data["sesis"] = $this->cbt->getAllKodeSesi();
        $data["kop"] = $this->dashboard->getSetting();
        $data["ujian"] = $this->dropdown->getAllJenisUjian();
        $data["mode"] = $mode;
        if ($mode == "1" || $mode == null) {
            $data["siswa"] = $this->cbt->getAllPesertaByRuang($tp->id_tp, $smt->id_smt);
            goto xPRBF;
        }
        $data["siswa"] = $this->cbt->getAllPesertaByKelas($tp->id_tp, $smt->id_smt);
        xPRBF:
        if ($this->ion_auth->is_admin()) {
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/cetak/pesertaujian");
            $this->load->view("_templates/dashboard/_footer");
            goto D7XwX;
        }
        $data["guru"] = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("cbt/cetak/pesertaujian");
        $this->load->view("members/guru/templates/footer");
        D7XwX:
    }
    public function pengawas()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $jenis_selected = $this->input->get("jenis", true);
        $jenis_ujian = $this->cbt->getJenisById($jenis_selected);
        $data = ["user" => $user, "judul" => "Jadwal Pengawas", "subjudul" => "Cetak Jadwal Pengawas", "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $id_jenis = $this->cbt->getDistinctJenisJadwal($tp->id_tp, $smt->id_smt);
        $ids = [];
        if (!(count($id_jenis) > 0)) {
            goto W9FVx;
        }
        foreach ($id_jenis as $jenis) {
            array_push($ids, $jenis->id_jenis);
        }
        W9FVx:
        if (count($ids) > 0) {
            $data["jenis"] = $this->cbt->getAllJenisUjianByArrJenis($ids);
            goto SDcTv;
        }
        $data["jenis"] = ['' => "belum ada jadwal ujian"];
        SDcTv:
        $filter_selected = $this->input->get("filter", true);
        $dari_selected = $this->input->get("dari", true);
        $sampai_selected = $this->input->get("sampai", true);
        $data["filter"] = ["0" => "Semua", "1" => "Tanggal"];
        $data["jenis_selected"] = $jenis_selected;
        $data["jenis_ujian"] = $jenis_ujian;
        $data["filter_selected"] = $filter_selected;
        $data["dari_selected"] = $dari_selected;
        $data["sampai_selected"] = $sampai_selected;
        $pengawas = [];
        if (!($jenis_selected != null)) {
            goto gDl2j;
        }
        $pengawas = $this->cbt->getAllPengawas($tp->id_tp, $smt->id_smt);
        gDl2j:
        $data["pengawas"] = $pengawas;
        $gurus = $this->dropdown->getAllGuru();
        $jadwals = [];
        if (!($jenis_selected != null)) {
            goto X9KuA;
        }
        $jadwals = $this->cbt->getJadwalByJenis($jenis_selected, "0", $dari_selected, $sampai_selected);
        X9KuA:
        $arrLevel = [];
        foreach ($jadwals as $jadwal) {
            if (in_array($jadwal->bank_level, $arrLevel)) {
                goto bgkBW;
            }
            array_push($arrLevel, $jadwal->bank_level);
            bgkBW:
        }
        $kelas_level = [];
        if (!(count($arrLevel) > 0)) {
            goto zdnAd;
        }
        $kelas_level = $this->cbt->getDistinctKelasLevel($tp->id_tp, $smt->id_smt, $arrLevel);
        $data["kelas_level"] = $kelas_level;
        zdnAd:
        $arrKls = [];
        foreach ($kelas_level as $kl) {
            array_push($arrKls, $kl->id_kelas);
        }
        $jadwal_pengawas = [];
        if (!(count($arrKls) > 0)) {
            goto YetxX;
        }
        $ruangs = $this->cbt->getDistinctRuang($tp->id_tp, $smt->id_smt, $arrKls);
        $data["ruang"] = $ruangs;
        foreach ($ruangs as $id_ruang => $ruang) {
            foreach ($ruang as $id_sesi => $sesi) {
                foreach ($kelas_level as $kl) {
                    foreach ($jadwals as $jadwal) {
                        if (!($jadwal->bank_level == $kl->level_id)) {
                            goto i2U31;
                        }
                        $jadwal_pengawas[$jadwal->tgl_mulai][$id_ruang][$id_sesi][$jadwal->kode] = $jadwal;
                        i2U31:
                    }
                }
            }
        }
        YetxX:
        $perRuang = [];
        $result = [];
        foreach ($jadwal_pengawas as $jadwal_pengawa) {
            foreach ($jadwal_pengawa as $r => $jp) {
                foreach ($jp as $s => $j) {
                    foreach ($j as $m => $km) {
                        $nr = $ruangs[$r][$s]->nama_ruang;
                        $ns = $ruangs[$r][$s]->nama_sesi;
                        $ir = $ruangs[$r][$s]->ruang_id;
                        $is = $ruangs[$r][$s]->sesi_id;
                        $sel = isset($pengawas[$km->id_jadwal]) && isset($pengawas[$km->id_jadwal][$ir]) && isset($pengawas[$km->id_jadwal][$ir][$is]) ? explode(",", $pengawas[$km->id_jadwal][$ir][$is]->id_guru) : [];
                        $jp = 0;
                        $jpp = count($sel);
                        $pw = '';
                        foreach ($sel as $p) {
                            if (!isset($gurus[$p])) {
                                goto IK4lT;
                            }
                            $pw .= $gurus[$p];
                            $jp += 1;
                            if (!($jp < $jpp)) {
                                goto sxk_z;
                            }
                            $pw .= "<br>";
                            sxk_z:
                            IK4lT:
                        }
                        $siswas = $this->cbt->getSiswaByRuang($tp->id_tp, $smt->id_smt, $ir, $is);
                        $forAdd = json_decode(json_encode(["jml_siswa" => count($siswas), "tanggal" => $km->tgl_mulai, "ruang" => $nr, "sesi" => $ns, "mapel" => $km->nama_mapel, "waktu" => $km->jam_ke, "pengawas" => $pw]));
                        array_push($result, $forAdd);
                        if (isset($perRuang[$forAdd->ruang])) {
                            array_push($perRuang[$forAdd->ruang], $forAdd);
                            goto wagZg;
                        }
                        $perRuang[$forAdd->ruang] = [];
                        array_push($perRuang[$forAdd->ruang], $forAdd);
                        wagZg:
                    }
                }
            }
        }
        $data["jadwals"] = $result;
        $data["jadwals_ruang"] = $perRuang;
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $data["ruang_sesi"] = $this->cbt->getRuangSesi($tp->id_tp, $smt->id_smt);
        $data["sesi"] = $this->dropdown->getAllSesi();
        if ($this->ion_auth->is_admin()) {
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/cetak/pengawas");
            $this->load->view("_templates/dashboard/_footer");
            goto gaN3f;
        }
        $data["guru"] = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("cbt/cetak/pengawas");
        $this->load->view("members/guru/templates/footer");
        gaN3f:
    }
}
