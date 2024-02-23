<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Userguru extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto lCgJb;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto zNkS0;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        zNkS0:
        lCgJb:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Users_model", "users");
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto yUma1;
        }
        $data = json_encode($data);
        yUma1:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function data()
    {
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $this->output_json($this->users->getUserGuru($tp->id_tp, $smt->id_smt), false);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $group = $this->ion_auth->get_users_groups($user->id)->row()->name;
        $data = ["user" => $user, "judul" => "User Management", "subjudul" => "Data User Guru", "profile" => $this->dashboard->getProfileAdmin($user->id), "setting" => $this->dashboard->getSetting()];
        if ($group === "admin") {
            $data["tp"] = $this->dashboard->getTahun();
            $data["tp_active"] = $this->dashboard->getTahunActive();
            $data["smt"] = $this->dashboard->getSemester();
            $data["smt_active"] = $this->dashboard->getSemesterActive();
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("users/guru/data");
            $this->load->view("_templates/dashboard/_footer");
            goto j9ZeI;
        }
        $id = $this->users->getGuruByUsername($user->username);
        $this->edit($id->id_guru);
        j9ZeI:
    }
    public function activate($id)
    {
        $guru = $this->users->getDataGuru($id);
        $nama = explode(" ", $guru->nama_guru);
        $first_name = $nama[0];
        $last_name = count($nama) > 2 ? $nama[1] : end($nama);
        $username = trim($guru->username);
        $password = trim($guru->password);
        $email = strtolower($guru->username) . "@guru.com";
        $additional_data = ["first_name" => $first_name, "last_name" => $last_name];
        $group = array("2");
        if ($this->ion_auth->username_check($username)) {
            $data = ["status" => false, "msg" => "Username " . $username . " tidak tersedia (sudah digunakan)."];
            goto QPlIr;
        }
        if ($this->ion_auth->email_check($email)) {
            $data = ["status" => false, "msg" => "Username " . $email . " tidak tersedia (sudah digunakan)."];
            goto OINRc;
        }
        $id_user = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
        $data = ["status" => true, "msg" => "Akun " . $guru->nama_guru . " diaktifkan."];
        $this->db->set("id_user", $id_user);
        $this->db->where("id_guru", $id);
        $this->db->update("master_guru");
        OINRc:
        QPlIr:
        $data["pass"] = $password;
        $this->output_json($data);
    }
    public function deactivate($id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            $data = ["status" => false, "msg" => "You must be an administrator to view this page."];
            goto mN066;
        }
        $id = (int) $id;
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $deleted = $this->ion_auth->delete_user($id);
            $data = ["status" => $deleted, "msg" => "telah dinonaktifkan."];
            goto G2uHp;
        }
        $data = ["status" => false, "msg" => "Anda bukan admin."];
        G2uHp:
        mN066:
        $this->output_json($data);
    }
    public function aktifkanSemua()
    {
        $guruAktif = $this->users->getGuruAktif();
        $jum = 0;
        foreach ($guruAktif as $guru) {
            if ($guru->aktif > 0) {
                goto LgDYd;
            }
            $this->activate($guru->id_guru);
            $jum += 1;
            LgDYd:
        }
        $data = ["status" => true, "jumlah" => $jum, "msg" => $jum . " Guru diaktifkan."];
        $this->output_json($data);
    }
    public function nonaktifkanSemua()
    {
        $guruAktif = $this->users->getGuruAktif();
        $jum = 0;
        foreach ($guruAktif as $guru) {
            if ($guru->aktif > 0) {
                $del = $this->deactivate($guru->id, '');
                $this->output_json($del);
                $jum += 1;
                goto ZC2wH;
            }
            ZC2wH:
        }
        $data = ["status" => true, "jumlah" => $jum, "msg" => $jum . " Guru dinonaktifkan."];
        $this->output_json($data);
    }
    public function edit($id)
    {
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $guru = $this->users->getDetailGuru($id);
        $users = $this->users->getUsers($guru->username);
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "User Management", "subjudul" => "Edit Data User", "setting" => $this->dashboard->getSetting()];
        $data["users"] = $users;
        $data["guru"] = $guru;
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $group = $this->ion_auth->get_users_groups($user->id)->row()->name;
        if ($group === "admin") {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $data["groups"] = $this->ion_auth->groups()->result();
            $data["kelass"] = $this->users->getKelas($tp->id_tp, $smt->id_smt);
            $data["mapels"] = $this->users->getMapel();
            $data["levels"] = $this->users->getLevelGuru();
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("users/guru/edit");
            $this->load->view("_templates/dashboard/_footer");
            goto kfxTk;
        }
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("users/guru/edit");
        $this->load->view("members/guru/templates/footer");
        kfxTk:
    }
    public function editLogin()
    {
        $id_guru = $this->input->post("id_guru", true);
        $username = $this->input->post("username", true);
        $pass = $this->input->post("new", true);
        $guru_lain = $this->master->getUserIdGuruByUsername($username);
        $this->form_validation->set_rules("old", $this->lang->line("change_password_validation_old_password_label"), "required");
        $this->form_validation->set_rules("new", $this->lang->line("change_password_validation_new_password_label"), "required|min_length[" . $this->config->item("min_password_length", "ion_auth") . "]|matches[new_confirm]");
        $this->form_validation->set_rules("new_confirm", $this->lang->line("change_password_validation_new_password_confirm_label"), "required");
        if ($guru_lain && $guru_lain->id_guru != $id_guru) {
            $data = ["status" => false, "errors" => ["username" => "Username sudah digunakan"]];
            goto g4DR6;
        }
        if ($this->form_validation->run() === FALSE) {
            $data = ["status" => false, "errors" => ["old" => form_error("old"), "new" => form_error("new"), "new_confirm" => form_error("new_confirm")]];
            goto I08bU;
        }
        $guru = $this->db->get_where("master_guru", "id_guru=\"" . $id_guru . "\"")->row();
        $nama = explode(" ", $guru->nama_guru);
        $first_name = $nama[0];
        $last_name = end($nama);
        $username = trim($username);
        $password = trim($pass);
        $email = strtolower($username) . "@guru.com";
        $additional_data = ["first_name" => $first_name, "last_name" => $last_name];
        $group = array("2");
        $user_guru = $this->db->get_where("users", "email=\"" . $email . "\"")->row();
        $deleted = true;
        if (!($user_guru != null)) {
            goto gCysJ;
        }
        $deleted = $this->ion_auth->delete_user((int) $user_guru->id);
        gCysJ:
        if ($deleted) {
            $id_user = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            $this->db->set("username", $username);
            $this->db->set("password", $password);
            $this->db->set("id_user", $id_user);
            $this->db->where("id_guru", $id_guru);
            $status = $this->db->update("master_guru");
            $msg = $status ? "Update berhasil" : "Gagal mengganti username/passsword";
            goto ZGZqJ;
        }
        $status = false;
        $msg = "Gagal mengganti username/passsword";
        ZGZqJ:
        $data["status"] = $status;
        $data["text"] = $msg;
        I08bU:
        g4DR6:
        $this->output_json($data);
    }
    function buangspasi($teks)
    {
        $teks = trim($teks);
        $hasil = $teks;
        vqzAt:
        if (!strpos($teks, " ")) {
            return $hasil;
        }
        $remove[] = "'";
        $remove[] = ".";
        $remove[] = " ";
        $hasil = str_replace($remove, '', $teks);
        goto vqzAt;
    }
    private function registerGuru($username, $password, $email, $additional_data, $group)
    {
        $reg = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
        $data["status"] = true;
        $data["id"] = $reg;
        if (!($reg == false)) {
            goto V4cKA;
        }
        $data["status"] = false;
        V4cKA:
        return $data;
    }
    public function reset_login()
    {
        $username = $this->input->get("username", true);
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            $data = ["status" => false, "msg" => "You must be an administrator to view this page."];
            goto GMmZr;
        }
        $this->db->where("login", $username);
        if ($this->db->delete("login_attempts")) {
            $data = ["status" => true, "msg" => " berhasil direset"];
            goto IUsdR;
        }
        $data = ["status" => false, "msg" => " gagal direset"];
        IUsdR:
        GMmZr:
        $this->output_json($data, true);
    }
}
