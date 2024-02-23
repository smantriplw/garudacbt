<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtnomorpeserta extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto j9brA;
        }
        if ($this->ion_auth->is_admin()) {
            goto VPlf5;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        VPlf5:
        j9brA:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->library("upload");
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto qRdoP;
        }
        $data = json_encode($data);
        qRdoP:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Nomor Peserta", "subjudul" => "Generate Nomor Peserta Ujian", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
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
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("cbt/nomorpeserta/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function saveNomor()
    {
        $input = json_decode($this->input->post("siswa", true));
        $arrNomor = $this->cbt->getAllNomorPeserta();
        $tp = $this->dashboard->getTahunActive();
        $update = false;
        foreach ($input as $in) {
            $nomorAda = isset($arrNomor[$in->id]) ? $arrNomor[$in->id] : null;
            if ($nomorAda != null && $nomorAda->nomor_peserta == $in->nomor && $nomorAda->id_siswa != $in->id) {
                $update = false;
                goto zCgW0;
            }
            $insert = ["id_nomor" => $in->id . $tp->id_tp, "id_siswa" => $in->id, "id_tp" => $tp->id_tp, "nomor_peserta" => $in->nomor];
            $update = $this->db->replace("cbt_nomor_peserta", $insert);
            zCgW0:
        }
        $this->output_json($update);
    }
    public function resetNomor()
    {
        $input = json_decode($this->input->get("kelas", true));
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $siswas = $this->cbt->getSiswaByKelasArray($tp->id_tp, $smt->id_smt, $input);
        foreach ($siswas as $siswa) {
            $insert = ["id_nomor" => $siswa->id_siswa . $tp->id_tp, "id_siswa" => $siswa->id_siswa, "id_tp" => $tp->id_tp, "nomor_peserta" => ''];
            $update = $this->db->replace("cbt_nomor_peserta", $insert);
        }
        $res["status"] = $update;
        $this->output_json($res);
    }
    public function getSiswaKelas($arr_kelas)
    {
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $kelas = json_decode(urldecode($arr_kelas));
        $siswas = $this->cbt->getSiswaByKelasArray($tp->id_tp, $smt->id_smt, $kelas);
        $arrNomor = $this->cbt->getAllNomorPeserta();
        $data["siswa"] = $siswas;
        $data["nomor"] = $arrNomor;
        $this->output_json($data);
    }
}
