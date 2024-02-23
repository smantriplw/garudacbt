<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class JurusanMapel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto m3i3R;
        }
        if ($this->ion_auth->is_admin()) {
            goto xNChw;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        xNChw:
        m3i3R:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Master_model", "master");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto s3OvJ;
        }
        $data = json_encode($data);
        s3OvJ:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $data = ["user" => $this->ion_auth->user()->row(), "judul" => "Jurusan Mata Kuliah", "subjudul" => "Data Jurusan Mata Kuliah"];
        $this->load->view("_templates/dashboard/_header.php", $data);
        $this->load->view("relasi/jurusanmapel/data");
        $this->load->view("_templates/dashboard/_footer.php");
    }
    public function data()
    {
        $this->output_json($this->master->getJurusanMapel(), false);
    }
    public function getJurusanId($id)
    {
        $this->output_json($this->master->getAllJurusan($id));
    }
    public function add()
    {
        $data = ["user" => $this->ion_auth->user()->row(), "judul" => "Tambah Jurusan Mata Kuliah", "subjudul" => "Tambah Data Jurusan Mata Kuliah", "mapel" => $this->master->getMapel()];
        $this->load->view("_templates/dashboard/_header.php", $data);
        $this->load->view("relasi/jurusanmapel/add");
        $this->load->view("_templates/dashboard/_footer.php");
    }
    public function edit($id)
    {
        $data = ["user" => $this->ion_auth->user()->row(), "judul" => "Edit Jurusan Mata Kuliah", "subjudul" => "Edit Data Jurusan Mata Kuliah", "mapel" => $this->master->getMapelById($id, true), "id_mapel" => $id, "all_jurusan" => $this->master->getAllJurusan(), "jurusan" => $this->master->getJurusanByIdMapel($id)];
        $this->load->view("_templates/dashboard/_header.php", $data);
        $this->load->view("relasi/jurusanmapel/edit");
        $this->load->view("_templates/dashboard/_footer.php");
    }
    public function save()
    {
        $method = $this->input->post("method", true);
        $this->form_validation->set_rules("mapel_id", "Mata Kuliah", "required");
        $this->form_validation->set_rules("jurusan_id[]", "Jurusan", "required");
        if ($this->form_validation->run() == FALSE) {
            $data = ["status" => false, "errors" => ["mapel_id" => form_error("mapel_id"), "jurusan_id[]" => form_error("jurusan_id[]")]];
            $this->output_json($data);
            goto s5dq6;
        }
        $mapel_id = $this->input->post("mapel_id", true);
        $jurusan_id = $this->input->post("jurusan_id", true);
        $input = [];
        foreach ($jurusan_id as $key => $val) {
            $input[] = ["mapel_id" => $mapel_id, "jurusan_id" => $val];
        }
        if ($method === "add") {
            $action = $this->master->create("jurusan_mapel", $input, true);
            goto g7DlY;
        }
        if (!($method === "edit")) {
            goto gofvi;
        }
        $id = $this->input->post("mapel_id", true);
        $this->master->delete("jurusan_mapel", $id, "mapel_id");
        $action = $this->master->create("jurusan_mapel", $input, true);
        gofvi:
        g7DlY:
        $data["status"] = $action ? TRUE : FALSE;
        s5dq6:
        $this->output_json($data);
    }
    public function delete()
    {
        $chk = $this->input->post("checked", true);
        if (!$chk) {
            $this->output_json(["status" => false]);
            goto wbY20;
        }
        if (!$this->master->delete("jurusan_mapel", $chk, "mapel_id")) {
            goto qizat;
        }
        $this->output_json(["status" => true, "total" => count($chk)]);
        qizat:
        wbY20:
    }
}
