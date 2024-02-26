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
use PhpOffice\PhpWord\PhpWord;
class Datajurusan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto TYxfZ;
        }
        if ($this->ion_auth->is_admin()) {
            goto gvA30;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        gvA30:
        TYxfZ:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Dropdown_model", "dropdown");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto JXkjd;
        }
        $data = json_encode($data);
        JXkjd:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Jurusan", "subjudul" => "Daftar Jurusan", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data["mapel_peminatan"] = $this->dropdown->getAllMapelPeminatan();
        $jurusans = $this->master->getDataJurusan();
        $jurusan_mapels = [];
        foreach ($jurusans as $jurusan) {
            $jurusan_mapels[$jurusan->id_jurusan] = $this->master->getDataJurusanMapel(explode(",", $jurusan->mapel_peminatan));
        }
        $data["jurusans"] = $jurusans;
        $data["jurusan_mapels"] = $jurusan_mapels;
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/jurusan/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function add()
    {
        $mapels = [];
        $check_mapel = $this->input->post("mapel", true);
        if (!$check_mapel) {
            goto LdNVR;
        }
        $row_mapels = count($this->input->post("mapel", true));
        $i = 0;
        Kk9kL:
        if (!($i <= $row_mapels)) {
            LdNVR:
            $insert = ["nama_jurusan" => $this->input->post("nama_jurusan", true), "kode_jurusan" => $this->input->post("kode_jurusan", true), "mapel_peminatan" => implode(",", $mapels)];
            $this->master->create("master_jurusan", $insert, false);
            $data["status"] = $insert;
            $this->output_json($data);
            // [PHPDeobfuscator] Implied return
            return;
        }
        array_push($mapels, $this->input->post("mapel[" . $i . "]", true));
        $i++;
        goto Kk9kL;
    }
    public function data()
    {
        $this->output_json($this->master->getDataTableJurusan(), false);
    }
    public function save()
    {
        $rows = count($this->input->post("nama_jurusan", true));
        $mode = $this->input->post("mode", true);
        $i = 1;
        crAWp:
        if (!($i <= $rows)) {
            if ($status) {
                if ($mode == "add") {
                    $this->master->create("master_jurusan", $insert, true);
                    $data["insert"] = $insert;
                    goto z2riS;
                }
                if (!($mode == "edit")) {
                    goto tphNg;
                }
                $this->master->update("master_jurusan", $update, "id_jurusan", null, true);
                $data["update"] = $update;
                tphNg:
                z2riS:
                goto r66Ly;
            }
            if (!isset($error)) {
                goto MhBIj;
            }
            $data["errors"] = $error;
            MhBIj:
            r66Ly:
            $data["status"] = $status;
            $this->output_json($data);
            // [PHPDeobfuscator] Implied return
            return;
        }
        $nama_jurusan = "nama_jurusan[" . $i . "]";
        $this->form_validation->set_rules($nama_jurusan, "Jurusan", "required");
        $this->form_validation->set_message("required", "{field} Wajib diisi");
        if ($this->form_validation->run() === FALSE) {
            $error[] = [$nama_jurusan => form_error($nama_jurusan)];
            $status = FALSE;
            goto z84Sc;
        }
        if ($mode == "add") {
            $insert[] = ["nama_jurusan" => $this->input->post($nama_jurusan, true)];
            goto oi0b_;
        }
        if (!($mode == "edit")) {
            goto fjJ8U;
        }
        $update[] = array("id_jurusan" => $this->input->post("id_jurusan[" . $i . "]", true), "nama_jurusan" => $this->input->post($nama_jurusan, true));
        fjJ8U:
        oi0b_:
        $status = TRUE;
        z84Sc:
        $i++;
        goto crAWp;
    }
    public function update()
    {
        $data = $this->master->updateJurusan();
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function delete()
    {
        $chk = $this->input->post("checked", true);
        if (!$chk) {
            $this->output_json(["status" => false, "total" => "Tidak ada data yang dipilih!"]);
            goto WGbBd;
        }
        $messages = [];
        $tables = [];
        $tabless = $this->db->list_tables();
        foreach ($tabless as $table) {
            $fields = $this->db->field_data($table);
            foreach ($fields as $field) {
                if (!($field->name == "id_jurusan" || $field->name == "jurusan_id")) {
                    goto KVuBX;
                }
                array_push($tables, $table);
                KVuBX:
            }
        }
        foreach ($tables as $table) {
            if (!($table != "master_jurusan")) {
                goto x9Zuv;
            }
            if ($table == "master_kelas") {
                $this->db->where_in("jurusan_id", $chk);
                $num = $this->db->count_all_results($table);
                goto CEjTo;
            }
            $this->db->where_in("id_jurusan", $chk);
            $num = $this->db->count_all_results($table);
            CEjTo:
            if (!($num > 0)) {
                goto FJSEm;
            }
            array_push($messages, $table);
            FJSEm:
            x9Zuv:
        }
        if (count($messages) > 0) {
            $this->output_json(["status" => false, "total" => "Data Jurusan digunakan di " . count($messages) . " tabel:<br>" . implode("<br>", $messages)]);
            goto V5CnT;
        }
        if (!$this->master->delete("master_jurusan", $chk, "id_jurusan")) {
            goto E8lNH;
        }
        $this->output_json(["status" => true, "total" => count($chk)]);
        E8lNH:
        V5CnT:
        WGbBd:
    }
    public function load_jurusan()
    {
        $data = $this->master->getJurusan();
        $this->output_json($data);
    }
    public function import($import_data = null)
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Import Jurusan", "subjudul" => "Import Jurusan", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        if (!($import_data != null)) {
            goto EGAdj;
        }
        $data["import"] = $import_data;
        EGAdj:
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("master/jurusan/import");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function preview()
    {
        $config["upload_path"] = "./uploads/import/";
        $config["allowed_types"] = "xls|xlsx|csv";
        $config["max_size"] = 2048;
        $config["encrypt_name"] = true;
        $this->load->library("upload", $config);
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
                goto DPrJC;
            case ".xls":
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                goto DPrJC;
            case ".csv":
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                goto DPrJC;
            default:
                echo "unknown file ext";
                die;
        }
        DPrJC:
        $spreadsheet = $reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $data = [];
        $i = 1;
        M_7fr:
        if (!($i < count($sheetData))) {
            unlink($file);
            echo json_encode($data);
            // [PHPDeobfuscator] Implied return
            return;
        }
        if (!($sheetData[$i][0] != null)) {
            goto ggbnJ;
        }
        $data[] = ["nama" => $sheetData[$i][1], "kode" => $sheetData[$i][2]];
        ggbnJ:
        $i++;
        goto M_7fr;
    }
    public function previewWord()
    {
        $config["upload_path"] = "./uploads/import/";
        $config["allowed_types"] = "docx";
        $config["max_size"] = 2048;
        $config["encrypt_name"] = true;
        $this->load->library("upload", $config);
        if (!$this->upload->do_upload("upload_file")) {
            $error = $this->upload->display_errors();
            echo $error;
            die;
        }
        $file = $this->upload->data("full_path");
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
        $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
        try {
            $htmlWriter->save("./uploads/temp/doc.html");
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
        }
        unlink($file);
        $text = file_get_contents("./uploads/temp/doc.html");
        $dom = new DOMDocument();
        $dom->loadHTML($text);
        $data = [];
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName("table");
        $rows = $tables->item(0)->getElementsByTagName("tr");
        $i = 1;
        wOCcY:
        if (!($i < $rows->count())) {
            echo json_encode($data);
            // [PHPDeobfuscator] Implied return
            return;
        }
        $cols = $rows[$i]->getElementsByTagName("td");
        $data[] = ["nama" => $cols->item(1)->nodeValue, "kode" => $cols->item(2)->nodeValue];
        $i++;
        goto wOCcY;
    }
    public function do_import()
    {
        $data = json_decode($this->input->post("jurusan", true));
        $jurusan = [];
        foreach ($data as $j) {
            $jurusan[] = ["nama_jurusan" => $j->nama, "kode_jurusan" => $j->kode];
        }
        $save = $this->master->create("master_jurusan", $jurusan, true);
        $this->output->set_content_type("application/json")->set_output($save);
    }
    function updateById()
    {
        $id = $this->input->post("id_jurusan");
        $nama = $this->input->post("username", true);
        $kode = $this->input->post("email", true);
        $this->db->set("nama_jurusan", $nama);
        $this->db->set("kode_jurusan", $kode);
        $this->db->where("id_jurusan", $id);
        return $this->db->update("master_jurusan");
    }
    public function hapusById()
    {
        $id = $this->input->post("id");
        $this->db->where("id_jurusan", $id);
        return $this->db->delete("master_jurusan");
    }
    function exist($table, $data)
    {
        $query = $this->db->get_where($table, $data);
        $count = $query->num_rows();
        if ($count === 0) {
            return false;
        }
        return true;
    }
}