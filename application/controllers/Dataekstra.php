<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
class Dataekstra extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto hgk1c;
        }
        if ($this->ion_auth->is_admin()) {
            goto gTkrq;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        gTkrq:
        hgk1c:
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
            goto fgk25;
        }
        $data = json_encode($data);
        fgk25:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Ekstrakurikuler", "subjudul" => "Data Mata Pelajaran", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["ekskul"] = $this->dropdown->getAllEkskul();
        $kelas = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $kelasEks = [];
        foreach ($kelas as $key => $kls) {
            $kelasEks[$key] = $this->kelas->getKelasEkskul($key, $tp->id_tp, $smt->id_smt);
        }
        $data["ekskul_kelas"] = $kelasEks;
        $data["kelas"] = $kelas;
        $data["pembimbing"] = $this->dropdown->getAllGuru();
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/ekstra/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function create()
    {
        $insert = ["nama_ekstra" => $this->input->post("nama_ekstra", true), "kode_ekstra" => $this->input->post("kode_ekstra", true)];
        $data = $this->master->create("master_ekstra", $insert);
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function read()
    {
        $this->datatables->select("*");
        $this->datatables->from("master_ekstra");
        echo $this->datatables->generate();
    }
    public function update()
    {
        $data = $this->master->updateEkstra();
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function delete($id)
    {
        $messages = [];
        $tables = [];
        $tabless = $this->db->list_tables();
        foreach ($tabless as $table) {
            $fields = $this->db->field_data($table);
            foreach ($fields as $field) {
                if (!($field->name == "id_ekstra" || $field->name == "ekstra_id")) {
                    goto a5Uc2;
                }
                array_push($tables, $table);
                a5Uc2:
            }
        }
        $this->output_json($tables);
        foreach ($tables as $table) {
            if (!($table != "master_ekstra")) {
                goto JFE4g;
            }
            $this->db->where("id_ekstra", $id);
            $num = $this->db->count_all_results($table);
            if (!($num > 0)) {
                goto T1w3H;
            }
            array_push($messages, $table);
            T1w3H:
            JFE4g:
        }
        if (count($messages) > 0) {
            $this->output_json(["status" => false, "total" => "Mapel digunakan di " . count($messages) . " tabel:<br>" . implode("<br>", $messages)]);
            goto vPL_9;
        }
        if ($this->master->delete("master_ekstra", [$id], "id_ekstra")) {
            $this->output_json(["status" => true, "message" => "Ekskul berhasil dihapus"]);
            goto wBxGA;
        }
        $this->output_json(["status" => false, "message" => "Ekskul gagal dihapus"]);
        wBxGA:
        vPL_9:
    }
    public function save()
    {
        $check_kelas = json_decode(json_encode(json_decode($this->input->post("kelas", true))));
        $tp = $this->master->getTahunActive()->id_tp;
        $smt = $this->master->getSemesterActive()->id_smt;
        $row_insert = 0;
        $update = [];
        foreach ($check_kelas as $key => $kls) {
            $check_ekskul = $this->input->post("ekskul" . $kls->kls_id, true);
            if (!$check_ekskul) {
                goto b39DM;
            }
            $row_ekskul = count($this->input->post("ekskul" . $kls->kls_id, true));
            $ekstra = [];
            $j = 0;
            Upt7c:
            if (!($j <= $row_ekskul)) {
                $ekstras = ["id_kelas_ekstra" => $kls->kls_id . $tp . $smt, "id_kelas" => $kls->kls_id, "id_tp" => $tp, "id_smt" => $smt, "ekstra" => serialize($ekstra)];
                $update[] = $this->db->replace("kelas_ekstra", $ekstras);
                b39DM:
            }
            $kelaseks = $this->input->post("ekskul" . $kls->kls_id . "[" . $j . "]", true);
            $ekstra[] = ["ekstra" => $kelaseks];
            $j++;
            goto Upt7c;
        }
        $res["status"] = true;
        $res["update"] = $update;
        $this->output_json($res);
    }
    public function import($import_data = null)
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Mata Pelajaran", "subjudul" => "Import Mata Pelajaran", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        if (!($import_data != null)) {
            goto BwWws;
        }
        $data["import"] = $import_data;
        BwWws:
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/ekstra/import");
        $this->load->view("_templates/dashboard/_footer");
    }
}
