<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Kelascatatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto o1A6i;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto UFbdk;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        UFbdk:
        o1A6i:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Kelas_model", "kelas");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto p_7f0;
        }
        $data = json_encode($data);
        p_7f0:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Catatan Guru", "subjudul" => "Catatan Selama Pembelajaran", "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $id_kelas = $this->input->get("kelas", true);
        $id_mapel = $this->input->get("mapel", true);
        $data["kelas_selected"] = $id_kelas;
        $data["mapel_selected"] = $id_mapel;
        if (!($id_kelas != null)) {
            goto Yp8o5;
        }
        $cat_kelas = $this->kelas->getCatatanMapelKelas($id_kelas, $id_mapel, $tp->id_tp, $smt->id_smt);
        foreach ($cat_kelas as $ck) {
            $ck->reading = unserialize($ck->reading);
        }
        $data["cat_kelas"] = $cat_kelas;
        $data["cat_siswa"] = $this->kelas->getCatatanMapelSiswa($tp->id_tp, $smt->id_smt, $id_kelas, $id_mapel);
        Yp8o5:
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $data["mapel"] = $this->dropdown->getAllMapel();
            $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("members/guru/kelas/catatan/data");
            $this->load->view("_templates/dashboard/_footer");
            goto CdRz2;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $data["guru"] = $guru;
        $data["id_guru"] = $guru->id_guru;
        $mapel_guru = $this->kelas->getGuruMapelKelas($guru->id_guru, $tp->id_tp, $smt->id_smt);
        $mapel = json_decode(json_encode(unserialize($mapel_guru->mapel_kelas)));
        $arrId = [];
        if (!($mapel != null)) {
            goto gk5ov;
        }
        foreach ($mapel as $mpl) {
            foreach ($mpl->kelas_mapel as $id_mapel) {
                array_push($arrId, $id_mapel->kelas);
            }
        }
        gk5ov:
        $kelasses = [];
        if (!(count($arrId) > 0)) {
            goto DLslL;
        }
        $kelasses = $this->dropdown->getAllKelasByArrayId($tp->id_tp, $smt->id_smt, $arrId);
        DLslL:
        $arrMapel = [];
        $arrKelas = [];
        if (!($mapel != null)) {
            goto yKxuk;
        }
        foreach ($mapel as $m) {
            $arrMapel[$m->id_mapel] = $m->nama_mapel;
            foreach ($m->kelas_mapel as $kls_mapel) {
                foreach ($kelasses as $key => $kelass) {
                    if (!($kls_mapel->kelas == $key)) {
                        goto LZBl6;
                    }
                    $arrKelas[$m->id_mapel][$key] = $kelass;
                    LZBl6:
                }
            }
        }
        yKxuk:
        $data["mapel"] = $arrMapel;
        $data["kelas"] = $arrKelas;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("members/guru/kelas/catatan/data");
        $this->load->view("members/guru/templates/footer");
        CdRz2:
    }
    public function siswa()
    {
        $id_siswa = $this->input->get("id");
        $id_mapel = $this->input->get("mapel");
        $id_kelas = $this->input->get("kelas");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Catatan Siswa", "subjudul" => "Catatan Siswa", "setting" => $this->dashboard->getSetting()];
        $tp = $this->master->getTahunActive();
        $smt = $this->master->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["siswa"] = $this->master->getSiswaById($id_siswa);
        $data["catatan_siswa"] = $this->kelas->getAllCatatanMapelSiswa($id_siswa, $id_mapel, $tp->id_tp, $smt->id_smt);
        $data["mapel"] = $id_mapel;
        $data["kelas"] = $id_kelas;
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("members/guru/kelas/catatan/persiswa");
            $this->load->view("_templates/dashboard/_footer");
            goto JW8dg;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $data["guru"] = $guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("members/guru/kelas/catatan/persiswa");
        $this->load->view("members/guru/templates/footer");
        JW8dg:
    }
    public function saveCatatanKelas()
    {
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $user = $this->ion_auth->user()->row();
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $id_kelas = $this->input->post("id_kelas");
        $id_mapel = $this->input->post("id_mapel", true);
        $text = $this->input->post("text", true);
        $level = $this->input->post("level", true);
        $tgl = date("Y-m-d");
        $data = ["id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "type" => "1", "id_mapel" => $id_mapel, "id_kelas" => $id_kelas, "id_guru" => $guru->id_guru, "level" => $level, "text" => $text, "reading" => serialize([])];
        $insert = $this->master->create("kelas_catatan_mapel", $data);
        $this->output_json($insert);
    }
    public function saveCatatanSiswa()
    {
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $user = $this->ion_auth->user()->row();
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $id_siswa = $this->input->post("id_siswa");
        $id_mapel = $this->input->post("id_mapel", true);
        $text = $this->input->post("text", true);
        $level = $this->input->post("level", true);
        $data = ["id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "type" => "2", "id_mapel" => $id_mapel, "id_siswa" => $id_siswa, "id_guru" => $guru->id_guru, "level" => $level, "text" => $text, "reading" => serialize([])];
        $insert = $this->master->create("kelas_catatan_mapel", $data);
        $this->output_json($insert);
    }
    public function hapus($id_catatan)
    {
        $delete = $this->master->delete("kelas_catatan_mapel", $id_catatan, "id_catatan");
        $this->output_json($delete);
    }
}
