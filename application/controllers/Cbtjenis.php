<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtjenis extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto Ny5dy;
        }
        if ($this->ion_auth->is_admin()) {
            goto dOLvd;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        dOLvd:
        Ny5dy:
        $this->load->library(["datatables", "form_validation"]);
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto z4B2u;
        }
        $data = json_encode($data);
        z4B2u:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Jenis Ujian", "subjudul" => "Data Jenis Ujian", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("cbt/jenis/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function data()
    {
        $this->load->model("Cbt_model", "cbt");
        $this->output_json($this->cbt->getJenis(), false);
    }
    public function add()
    {
        $this->load->model("Master_model", "master");
        $insert = ["nama_jenis" => $this->input->post("nama_jenis", true), "kode_jenis" => $this->input->post("kode_jenis", true)];
        $this->master->create("cbt_jenis", $insert, false);
        $data["status"] = $insert;
        $this->output_json($data);
    }
    public function update()
    {
        $this->load->model("Cbt_model", "cbt");
        $data = $this->cbt->updateJenis();
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function delete()
    {
        $this->load->model("Master_model", "master");
        $chk = $this->input->post("checked", true);
        if (!$chk) {
            $this->output_json(["status" => false]);
            goto rQKgA;
        }
        if (!$this->master->delete("cbt_jenis", $chk, "id_jenis")) {
            goto M2n5z;
        }
        $this->output_json(["status" => true, "total" => count($chk)]);
        M2n5z:
        rQKgA:
    }
    public function saveLog($type, $desc)
    {
        $this->load->model("Log_model", "logging");
        $user = $this->ion_auth->user()->row();
        $this->logging->saveLog($type, $desc);
    }
}
