<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->ion_auth->logged_in()) {
            goto Lufou;
        }
        redirect("auth");
        Lufou:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Users_model", "users");
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "admindashboard");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function is_admin()
    {
        if ($this->ion_auth->is_admin()) {
            goto Jv5BW;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        Jv5BW:
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto J7_na;
        }
        $data = json_encode($data);
        J7_na:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function data($id = null)
    {
        $this->is_admin();
        $this->output_json($this->users->getDataUsers($id), false);
    }
    public function index()
    {
        $this->is_admin();
        $data = ["user" => $this->ion_auth->user()->row(), "judul" => "User Management", "subjudul" => "Data User"];
        $data["tp"] = $this->admindashboard->getTahun();
        $data["tp_active"] = $this->admindashboard->getTahunActive();
        $data["smt"] = $this->admindashboard->getSemester();
        $data["smt_active"] = $this->admindashboard->getSemesterActive();
        $this->load->view("_templates/dashboard/header.php", $data);
        $this->load->view("users/data");
        $this->load->view("_templates/dashboard/footer.php");
    }
    public function edit($id)
    {
        $level = $this->ion_auth->get_users_groups($id)->result();
        $data = ["user" => $this->ion_auth->user()->row(), "judul" => "User Management", "subjudul" => "Edit Data User", "users" => $this->ion_auth->user($id)->row(), "groups" => $this->ion_auth->groups()->result(), "level" => $level[0]];
        $this->load->view("_templates/dashboard/header.php", $data);
        $this->load->view("users/edit");
        $this->load->view("_templates/dashboard/footer.php");
    }
    public function edit_info()
    {
        $this->is_admin();
        $this->form_validation->set_rules("username", "Username", "required");
        $this->form_validation->set_rules("first_name", "First Name", "required");
        $this->form_validation->set_rules("last_name", "Last Name", "required");
        $this->form_validation->set_rules("email", "Email", "required|valid_email");
        if ($this->form_validation->run() === FALSE) {
            $data["status"] = false;
            $data["errors"] = ["username" => form_error("username"), "first_name" => form_error("first_name"), "last_name" => form_error("last_name"), "email" => form_error("email")];
            goto M2Ksh;
        }
        $id = $this->input->post("id", true);
        $input = ["username" => $this->input->post("username", true), "first_name" => $this->input->post("first_name", true), "last_name" => $this->input->post("last_name", true), "email" => $this->input->post("email", true)];
        $update = $this->master->update("users", $input, "id", $id);
        $data["status"] = $update ? true : false;
        M2Ksh:
        $this->output_json($data);
    }
    public function edit_status()
    {
        $this->is_admin();
        $this->form_validation->set_rules("status", "Status", "required");
        if ($this->form_validation->run() === FALSE) {
            $data["status"] = false;
            $data["errors"] = ["status" => form_error("status")];
            goto lZhYs;
        }
        $id = $this->input->post("id", true);
        $input = ["active" => $this->input->post("status", true)];
        $update = $this->master->update("users", $input, "id", $id);
        $data["status"] = $update ? true : false;
        lZhYs:
        $this->output_json($data);
    }
    public function edit_level()
    {
        $this->is_admin();
        $this->form_validation->set_rules("level", "Level", "required");
        if ($this->form_validation->run() === FALSE) {
            $data["status"] = false;
            $data["errors"] = ["level" => form_error("level")];
            goto wH9ON;
        }
        $id = $this->input->post("id", true);
        $input = ["group_id" => $this->input->post("level", true)];
        $update = $this->master->update("users_groups", $input, "user_id", $id);
        $data["status"] = $update ? true : false;
        wH9ON:
        $this->output_json($data);
    }
    public function change_password()
    {
        $this->form_validation->set_rules("old", $this->lang->line("change_password_validation_old_password_label"), "required");
        $this->form_validation->set_rules("new", $this->lang->line("change_password_validation_new_password_label"), "required|min_length[" . $this->config->item("min_password_length", "ion_auth") . "]|matches[new_confirm]");
        $this->form_validation->set_rules("new_confirm", $this->lang->line("change_password_validation_new_password_confirm_label"), "required");
        if ($this->form_validation->run() === FALSE) {
            $data = ["status" => false, "errors" => ["old" => form_error("old"), "new" => form_error("new"), "new_confirm" => form_error("new_confirm")]];
            goto flnyn;
        }
        $identity = $this->session->userdata("identity");
        $change = $this->ion_auth->change_password($identity, $this->input->post("old"), $this->input->post("new"));
        if ($change) {
            $data["status"] = true;
            goto Dh7Xo;
        }
        $data = ["status" => false, "msg" => $this->ion_auth->errors()];
        Dh7Xo:
        flnyn:
        $this->output_json($data);
    }
    public function delete($id)
    {
        $this->is_admin();
        $data["status"] = $this->ion_auth->delete_user($id) ? true : false;
        $this->output_json($data);
    }
}
