<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Settings extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto pQe8i;
        }
        if ($this->ion_auth->is_admin()) {
            goto LhCVZ;
        }
        show_error("Hanya Admin yang boleh mengakses halaman ini", 403, "Akses dilarang");
        LhCVZ:
        pQe8i:
        $this->load->library("upload");
        $this->load->model("Settings_model", "settings");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->helper("directory");
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto eJTkC;
        }
        $data = json_encode($data);
        eJTkC:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Profile Sekolah", "subjudul" => '', "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("setting/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function dbManager()
    {
        $data = ["user" => $this->ion_auth->user()->row(), "judul" => "Backup dan Restore", "subjudul" => "Backup dan Restore"];
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data["setting"] = $this->settings->getSetting();
        $data["list"] = directory_map("./backups/");
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("setting/db");
        $this->load->view("_templates/dashboard/_footer");
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
                goto wryry;
            }
            $result = $this->upload->data();
            $data["src"] = base_url() . "uploads/settings/" . $result["file_name"];
            $data["filename"] = pathinfo($result["file_name"], PATHINFO_FILENAME);
            $data["status"] = true;
            wryry:
            $data["type"] = $_FILES["logo"]["type"];
            $data["size"] = $_FILES["logo"]["size"];
            goto APw1U;
        }
        $data["src"] = '';
        APw1U:
        $this->output_json($data);
    }
    function deleteFile()
    {
        $src = $this->input->post("src");
        $file_name = str_replace(base_url(), '', $src);
        if (!unlink($file_name)) {
            goto XHO6p;
        }
        echo "File Delete Successfully";
        XHO6p:
    }
    public function saveSetting()
    {
        $sekolah = $this->input->post("nama_sekolah", true);
        $nss = $this->input->post("nss", true);
        $npsn = $this->input->post("npsn", true);
        $jenjang = $this->input->post("jenjang", true);
        $satuan_pendidikan = $this->input->post("satuan_pendidikan", true);
        $alamat = $this->input->post("alamat", true);
        $kota = $this->input->post("kota", true);
        $desa = $this->input->post("desa", true);
        $kec = $this->input->post("kec", true);
        $prov = $this->input->post("provinsi", true);
        $kodepos = $this->input->post("kode_pos", true);
        $tlp = $this->input->post("tlp", true);
        $web = $this->input->post("web", true);
        $fax = $this->input->post("fax", true);
        $email = $this->input->post("email", true);
        $kepsek = $this->input->post("kepsek", true);
        $nip = $this->input->post("nip", true);
        $tanda_tangan = $this->input->post("tanda_tangan", true);
        $nama_aplikasi = $this->input->post("nama_aplikasi", true);
        $logo_kanan = $this->input->post("logo_kanan", true);
        $logo_kiri = $this->input->post("logo_kiri", true);
        $insert = ["sekolah" => $sekolah, "nss" => $nss, "npsn" => $npsn, "jenjang" => $jenjang, "satuan_pendidikan" => $satuan_pendidikan, "alamat" => $alamat, "desa" => $desa, "kota" => $kota, "kecamatan" => $kec, "kode_pos" => $kodepos, "provinsi" => $prov, "web" => $web, "fax" => $fax, "email" => $email, "telp" => $tlp, "kepsek" => $kepsek, "nip" => $nip, "tanda_tangan" => str_replace(base_url(), '', $tanda_tangan), "nama_aplikasi" => $nama_aplikasi, "logo_kanan" => str_replace(base_url(), '', $logo_kanan), "logo_kiri" => str_replace(base_url(), '', $logo_kiri)];
        $this->db->where("id_setting", 1);
        $update = $this->db->update("setting", $insert);
        $this->output_json($update);
    }
}
