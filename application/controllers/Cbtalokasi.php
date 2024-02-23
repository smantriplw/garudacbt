<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtalokasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto m_c2i;
        }
        if ($this->ion_auth->is_admin()) {
            goto y_nuM;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        y_nuM:
        m_c2i:
        $this->load->library(["datatables", "form_validation"]);
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto KmQuy;
        }
        $data = json_encode($data);
        KmQuy:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Alokasi Waktu", "subjudul" => "Alokasi Waktu Ujian", "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $id_jenis = $this->cbt->getDistinctJenisJadwal($tp->id_tp, $smt->id_smt);
        $ids = [];
        if (!(count($id_jenis) > 0)) {
            goto c_F8b;
        }
        foreach ($id_jenis as $jenis) {
            array_push($ids, $jenis->id_jenis);
        }
        c_F8b:
        if (count($ids) > 0) {
            $data["jenis"] = $this->cbt->getAllJenisUjianByArrJenis($ids);
            goto qPEPd;
        }
        $data["jenis"] = ['' => "belum ada jadwal ujian"];
        qPEPd:
        $jenis_selected = $this->input->get("jenis", true);
        $level_selected = $this->input->get("level", true);
        $filter_selected = $this->input->get("filter", true);
        $dari_selected = $this->input->get("dari", true);
        $sampai_selected = $this->input->get("sampai", true);
        $data["filter"] = ["0" => "Semua", "1" => "Tanggal"];
        $data["jenis_selected"] = $jenis_selected;
        $data["level_selected"] = $level_selected;
        $data["filter_selected"] = $filter_selected;
        $data["dari_selected"] = $dari_selected;
        $data["sampai_selected"] = $sampai_selected;
        $jadwals = [];
        if (!($jenis_selected != null && $level_selected != null)) {
            goto xA7HJ;
        }
        $jadwals = $this->cbt->getJadwalByJenis($jenis_selected, $level_selected, $dari_selected, $sampai_selected);
        xA7HJ:
        $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["ruang"] = $this->dropdown->getAllRuang();
        $levels = [];
        if ($setting->jenjang == "1") {
            $levels = ["0" => "Pilih Level", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6"];
            goto YJxFD;
        }
        if ($setting->jenjang == "2") {
            $levels = ["0" => "Pilih Level", "7" => "7", "8" => "8", "9" => "9"];
            goto YJxFD;
        }
        if ($setting->jenjang == "3") {
            $levels = ["0" => "Pilih Level", "10" => "10", "11" => "11", "12" => "12"];
            goto HtE7f;
        }
        HtE7f:
        YJxFD:
        $data["levels"] = $levels;
        $ret = [];
        foreach ($jadwals as $key => $row) {
            if (isset($ret[$row->tgl_mulai])) {
                array_push($ret[$row->tgl_mulai], $row);
                goto z4hgK;
            }
            $ret[$row->tgl_mulai] = [];
            array_push($ret[$row->tgl_mulai], $row);
            z4hgK:
        }
        $data["jadwals"] = $jadwals;
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("cbt/alokasi/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function saveAlokasi()
    {
        $input = json_decode($this->input->post("alokasi", true));
        $insert = [];
        foreach ($input as $d) {
            if (!($d->id_jadwal != "0")) {
                goto SzYpZ;
            }
            array_push($insert, ["id_jadwal" => $d->id_jadwal, "jam_ke" => $d->jam_ke]);
            SzYpZ:
        }
        $update = $this->db->update_batch("cbt_jadwal", $insert, "id_jadwal");
        $data["status"] = $update;
        $this->output_json($data);
    }
}
