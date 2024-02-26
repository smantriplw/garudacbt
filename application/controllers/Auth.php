<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Auth extends CI_Controller
{
    public $data = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library("form_validation");
        $this->load->helper(["url", "language"]);
        $this->form_validation->set_error_delimiters($this->config->item("error_start_delimiter", "ion_auth"), $this->config->item("error_end_delimiter", "ion_auth"));
        $this->lang->load("auth");
    }
    public function output_json($data)
    {
        $this->output->set_content_type("application/json")->set_output(json_encode($data));
    }
    public function index()
    {
        $this->load->model("Settings_model", "settings");
        if (!(count($this->db->list_tables()) == 0)) {
            goto nl8br;
        }
        redirect("install");
        nl8br:
        $setting = $this->settings->getSetting();
        if (!($setting == null)) {
            goto O1PM3;
        }
        redirect("install");
        O1PM3:
        if (!$this->ion_auth->logged_in()) {
            goto NU2EE;
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $group = $this->ion_auth->get_users_groups($user_id)->row()->name;
        redirect("dashboard");
        NU2EE:
        $this->data["setting"] = $setting;
        $this->data["identity"] = ["name" => "identity", "id" => "identity", "type" => "text", "placeholder" => "Username", "autofocus" => "autofocus", "class" => "form-control", "autocomplete" => "off"];
        $this->data["password"] = ["name" => "password", "id" => "password", "type" => "password", "placeholder" => "Password", "class" => "form-control"];
        $this->data["message"] = validation_errors() ? validation_errors() : $this->session->flashdata("message");
        $this->load->view("_templates/auth/_header", $this->data);
        $this->load->view("auth/login");
        $this->load->view("_templates/auth/_footer");
    }
    public function cek_login()
    {
        $this->form_validation->set_rules("identity", str_replace(":", '', $this->lang->line("login_identity_label")), "required|trim");
        $this->form_validation->set_rules("password", str_replace(":", '', $this->lang->line("login_password_label")), "required|trim");
        if ($this->form_validation->run() === TRUE) {
            $remember = (bool) $this->input->post("remember");
            if ($this->ion_auth->login($this->input->post("identity"), $this->input->post("password"), $remember)) {
                $this->cek_akses();
                goto JBzz7;
            }
            if ($this->ion_auth->is_max_login_attempts_exceeded($this->input->post("identity"))) {
                $data = ["status" => false, "failed" => "Anda sudah 3x melakukan percobaan login, silakan hubungi Administrator", "akses" => "attempts"];
                goto vGeXp;
            }
            $data = ["status" => false, "failed" => "Incorrect Login", "akses" => "no attempts"];
            vGeXp:
            $this->output_json($data);
            JBzz7:
            goto v7ZAJ;
        }
        $invalid = ["identity" => form_error("identity"), "password" => form_error("password")];
        $data = ["status" => false, "invalid" => $invalid, "akses" => "no valid"];
        $this->output_json($data);
        v7ZAJ:
    }
    public function cek_akses()
    {
        if (!$this->ion_auth->logged_in()) {
            $status = false;
            $url = "auth";
            goto bqB08;
        }
        $status = true;
        $this->load->model("Log_model", "logging");
        $this->logging->saveLog(1, "Login");
        $url = "dashboard";
        bqB08:
        $data = ["status" => $status, "url" => $url];
        $this->output_json($data);
    }
    public function logout()
    {
        $this->ion_auth->logout();
        redirect("login", "refresh");
    }
    public function change_password()
    {
        $this->form_validation->set_rules("old", $this->lang->line("change_password_validation_old_password_label"), "required");
        $this->form_validation->set_rules("new", $this->lang->line("change_password_validation_new_password_label"), "required|min_length[" . $this->config->item("min_password_length", "ion_auth") . "]|matches[new_confirm]");
        $this->form_validation->set_rules("new_confirm", $this->lang->line("change_password_validation_new_password_confirm_label"), "required");
        if ($this->ion_auth->logged_in()) {
            goto oCzo5;
        }
        redirect("auth/login", "refresh");
        oCzo5:
        $user = $this->ion_auth->user()->row();
        if ($this->form_validation->run() === FALSE) {
            $this->data["message"] = validation_errors() ? validation_errors() : $this->session->flashdata("message");
            $this->data["min_password_length"] = $this->config->item("min_password_length", "ion_auth");
            $this->data["old_password"] = ["name" => "old", "id" => "old", "type" => "password"];
            $this->data["new_password"] = ["name" => "new", "id" => "new", "type" => "password", "pattern" => "^.{" . $this->data["min_password_length"] . "}.*\$"];
            $this->data["new_password_confirm"] = ["name" => "new_confirm", "id" => "new_confirm", "type" => "password", "pattern" => "^.{" . $this->data["min_password_length"] . "}.*\$"];
            $this->data["user_id"] = ["name" => "user_id", "id" => "user_id", "type" => "hidden", "value" => $user->id];
            $this->_render_page("authDIRECTORY_SEPARATORchange_password", $this->data);
            goto l5rAk;
        }
        $identity = $this->session->userdata("identity");
        $change = $this->ion_auth->change_password($identity, $this->input->post("old"), $this->input->post("new"));
        if ($change) {
            $this->session->set_flashdata("message", $this->ion_auth->messages());
            $this->logout();
            goto ODxnX;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        redirect("auth/change_password", "refresh");
        ODxnX:
        l5rAk:
    }
    public function forgot_password()
    {
        $this->data["title"] = $this->lang->line("forgot_password_heading");
        if ($this->config->item("identity", "ion_auth") != "email") {
            $this->form_validation->set_rules("identity", $this->lang->line("forgot_password_identity_label"), "required");
            goto ssaAt;
        }
        $this->form_validation->set_rules("identity", $this->lang->line("forgot_password_validation_email_label"), "required|valid_email");
        ssaAt:
        if ($this->form_validation->run() === FALSE) {
            $this->data["type"] = $this->config->item("identity", "ion_auth");
            $this->data["identity"] = ["name" => "identity", "id" => "identity", "class" => "form-control", "autocomplete" => "off", "autofocus" => "autofocus"];
            if ($this->config->item("identity", "ion_auth") != "email") {
                $this->data["identity_label"] = $this->lang->line("forgot_password_identity_label");
                goto cDI4y;
            }
            $this->data["identity_label"] = $this->lang->line("forgot_password_email_identity_label");
            cDI4y:
            $this->data["message"] = validation_errors() ? validation_errors() : $this->session->flashdata("message");
            $this->load->view("_templates/auth/_header", $this->data);
            $this->load->view("auth/forgot_password");
            $this->load->view("_templates/auth/_footer");
            goto o7QCw;
        }
        $identity_column = $this->config->item("identity", "ion_auth");
        $identity = $this->ion_auth->where($identity_column, $this->input->post("identity"))->users()->row();
        if (!empty($identity)) {
            goto UZ9yt;
        }
        if ($this->config->item("identity", "ion_auth") != "email") {
            $this->ion_auth->set_error("forgot_password_identity_not_found");
            goto b30KW;
        }
        $this->ion_auth->set_error("forgot_password_email_not_found");
        b30KW:
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        redirect("auth/forgot_password", "refresh");
        UZ9yt:
        $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item("identity", "ion_auth")});
        if ($forgotten) {
            $this->session->set_flashdata("success", $this->ion_auth->messages());
            redirect("auth/forgot_password", "refresh");
            goto eXrp2;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        redirect("auth/forgot_password", "refresh");
        eXrp2:
        o7QCw:
    }
    public function reset_password($code = NULL)
    {
        if ($code) {
            goto oSt5X;
        }
        show_404();
        oSt5X:
        $this->data["title"] = $this->lang->line("reset_password_heading");
        $user = $this->ion_auth->forgotten_password_check($code);
        if ($user) {
            $this->form_validation->set_rules("new", $this->lang->line("reset_password_validation_new_password_label"), "required|min_length[" . $this->config->item("min_password_length", "ion_auth") . "]|matches[new_confirm]");
            $this->form_validation->set_rules("new_confirm", $this->lang->line("reset_password_validation_new_password_confirm_label"), "required");
            if ($this->form_validation->run() === FALSE) {
                $this->data["message"] = validation_errors() ? validation_errors() : $this->session->flashdata("message");
                $this->data["min_password_length"] = $this->config->item("min_password_length", "ion_auth");
                $this->data["new_password"] = ["name" => "new", "id" => "new", "type" => "password", "pattern" => "^.{" . $this->data["min_password_length"] . "}.*\$"];
                $this->data["new_password_confirm"] = ["name" => "new_confirm", "id" => "new_confirm", "type" => "password", "pattern" => "^.{" . $this->data["min_password_length"] . "}.*\$"];
                $this->data["user_id"] = ["name" => "user_id", "id" => "user_id", "type" => "hidden", "value" => $user->id];
                $this->data["csrf"] = $this->_get_csrf_nonce();
                $this->data["code"] = $code;
                $this->load->view("_templates/auth/_header");
                $this->load->view("auth/reset_password", $this->data);
                $this->load->view("_templates/auth/_footer");
                goto Se_vZ;
            }
            $identity = $user->{$this->config->item("identity", "ion_auth")};
            if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post("user_id")) {
                $this->ion_auth->clear_forgotten_password_code($identity);
                show_error($this->lang->line("error_csrf"));
                goto DWT0T;
            }
            $change = $this->ion_auth->reset_password($identity, $this->input->post("new"));
            if ($change) {
                $this->session->set_flashdata("message", $this->ion_auth->messages());
                redirect("auth/login", "refresh");
                goto AqBub;
            }
            $this->session->set_flashdata("message", $this->ion_auth->errors());
            redirect("auth/reset_password/" . $code, "refresh");
            AqBub:
            DWT0T:
            Se_vZ:
            goto PXjue;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        redirect("auth/forgot_password", "refresh");
        PXjue:
    }
    public function activate($id, $code = FALSE)
    {
        $activation = FALSE;
        if ($code !== FALSE) {
            $activation = $this->ion_auth->activate($id, $code);
            goto v8imv;
        }
        if (!$this->ion_auth->is_admin()) {
            goto wDD1m;
        }
        $activation = $this->ion_auth->activate($id);
        wDD1m:
        v8imv:
        if ($activation) {
            $this->session->set_flashdata("message", $this->ion_auth->messages());
            redirect("auth", "refresh");
            goto NAnTM;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        redirect("auth/forgot_password", "refresh");
        NAnTM:
    }
    public function deactivate($id = NULL)
    {
        if (!(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())) {
            goto ZY2E7;
        }
        show_error("You must be an administrator to view this page.");
        ZY2E7:
        $id = (int) $id;
        $this->load->library("form_validation");
        $this->form_validation->set_rules("confirm", $this->lang->line("deactivate_validation_confirm_label"), "required");
        $this->form_validation->set_rules("id", $this->lang->line("deactivate_validation_user_id_label"), "required|alpha_numeric");
        if ($this->form_validation->run() === FALSE) {
            $this->data["csrf"] = $this->_get_csrf_nonce();
            $this->data["user"] = $this->ion_auth->user($id)->row();
            $this->_render_page("authDIRECTORY_SEPARATORdeactivate_user", $this->data);
            goto jLkYU;
        }
        if (!($this->input->post("confirm") == "yes")) {
            goto KMULA;
        }
        if (!($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post("id"))) {
            goto i3k9G;
        }
        show_error($this->lang->line("error_csrf"));
        i3k9G:
        if (!($this->ion_auth->logged_in() && $this->ion_auth->is_admin())) {
            goto y7_5K;
        }
        $this->ion_auth->deactivate($id);
        y7_5K:
        KMULA:
        redirect("auth", "refresh");
        jLkYU:
    }
    public function create_user()
    {
        $this->data["title"] = $this->lang->line("create_user_heading");
        if (!(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())) {
            goto Rog18;
        }
        redirect("auth", "refresh");
        Rog18:
        $tables = $this->config->item("tables", "ion_auth");
        $identity_column = $this->config->item("identity", "ion_auth");
        $this->data["identity_column"] = $identity_column;
        $this->form_validation->set_rules("first_name", $this->lang->line("create_user_validation_fname_label"), "trim|required");
        $this->form_validation->set_rules("last_name", $this->lang->line("create_user_validation_lname_label"), "trim|required");
        if ($identity_column !== "email") {
            $this->form_validation->set_rules("identity", $this->lang->line("create_user_validation_identity_label"), "trim|required|is_unique[" . $tables["users"] . "." . $identity_column . "]");
            $this->form_validation->set_rules("email", $this->lang->line("create_user_validation_email_label"), "trim|required|valid_email");
            goto DPeJu;
        }
        $this->form_validation->set_rules("email", $this->lang->line("create_user_validation_email_label"), "trim|required|valid_email|is_unique[" . $tables["users"] . ".email]");
        DPeJu:
        $this->form_validation->set_rules("phone", $this->lang->line("create_user_validation_phone_label"), "trim");
        $this->form_validation->set_rules("company", $this->lang->line("create_user_validation_company_label"), "trim");
        $this->form_validation->set_rules("password", $this->lang->line("create_user_validation_password_label"), "required|min_length[" . $this->config->item("min_password_length", "ion_auth") . "]|matches[password_confirm]");
        $this->form_validation->set_rules("password_confirm", $this->lang->line("create_user_validation_password_confirm_label"), "required");
        if (!($this->form_validation->run() === TRUE)) {
            goto IGEPd;
        }
        $email = strtolower($this->input->post("email"));
        $identity = $identity_column === "email" ? $email : $this->input->post("identity");
        $password = $this->input->post("password");
        $additional_data = ["first_name" => $this->input->post("first_name"), "last_name" => $this->input->post("last_name"), "company" => $this->input->post("company"), "phone" => $this->input->post("phone")];
        IGEPd:
        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
            $this->session->set_flashdata("message", $this->ion_auth->messages());
            redirect("auth", "refresh");
            goto RizJx;
        }
        $this->data["message"] = validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata("message"));
        $this->data["first_name"] = ["name" => "first_name", "id" => "first_name", "type" => "text", "value" => $this->form_validation->set_value("first_name")];
        $this->data["last_name"] = ["name" => "last_name", "id" => "last_name", "type" => "text", "value" => $this->form_validation->set_value("last_name")];
        $this->data["identity"] = ["name" => "identity", "id" => "identity", "type" => "text", "value" => $this->form_validation->set_value("identity")];
        $this->data["email"] = ["name" => "email", "id" => "email", "type" => "text", "value" => $this->form_validation->set_value("email")];
        $this->data["company"] = ["name" => "company", "id" => "company", "type" => "text", "value" => $this->form_validation->set_value("company")];
        $this->data["phone"] = ["name" => "phone", "id" => "phone", "type" => "text", "value" => $this->form_validation->set_value("phone")];
        $this->data["password"] = ["name" => "password", "id" => "password", "type" => "password", "value" => $this->form_validation->set_value("password")];
        $this->data["password_confirm"] = ["name" => "password_confirm", "id" => "password_confirm", "type" => "password", "value" => $this->form_validation->set_value("password_confirm")];
        $this->_render_page("authDIRECTORY_SEPARATORcreate_user", $this->data);
        RizJx:
    }
    public function redirectUser()
    {
        if (!$this->ion_auth->is_admin()) {
            goto q1ee5;
        }
        redirect("auth", "refresh");
        q1ee5:
        redirect("/", "refresh");
    }
    public function edit_user($id)
    {
        $this->data["title"] = $this->lang->line("edit_user_heading");
        if (!(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
            goto AgTXn;
        }
        redirect("auth", "refresh");
        AgTXn:
        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();
        $this->form_validation->set_rules("first_name", $this->lang->line("edit_user_validation_fname_label"), "trim|required");
        $this->form_validation->set_rules("last_name", $this->lang->line("edit_user_validation_lname_label"), "trim|required");
        $this->form_validation->set_rules("phone", $this->lang->line("edit_user_validation_phone_label"), "trim");
        $this->form_validation->set_rules("company", $this->lang->line("edit_user_validation_company_label"), "trim");
        if (!(isset($_POST) && !empty($_POST))) {
            goto aeu_X;
        }
        if (!($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post("id"))) {
            goto jued3;
        }
        show_error($this->lang->line("error_csrf"));
        jued3:
        if (!$this->input->post("password")) {
            goto dNPZd;
        }
        $this->form_validation->set_rules("password", $this->lang->line("edit_user_validation_password_label"), "required|min_length[" . $this->config->item("min_password_length", "ion_auth") . "]|matches[password_confirm]");
        $this->form_validation->set_rules("password_confirm", $this->lang->line("edit_user_validation_password_confirm_label"), "required");
        dNPZd:
        if (!($this->form_validation->run() === TRUE)) {
            goto mqv23;
        }
        $data = ["first_name" => $this->input->post("first_name"), "last_name" => $this->input->post("last_name"), "company" => $this->input->post("company"), "phone" => $this->input->post("phone")];
        if (!$this->input->post("password")) {
            goto cxDLR;
        }
        $data["password"] = $this->input->post("password");
        cxDLR:
        if (!$this->ion_auth->is_admin()) {
            goto xSWMn;
        }
        $this->ion_auth->remove_from_group('', $id);
        $groupData = $this->input->post("groups");
        if (!(isset($groupData) && !empty($groupData))) {
            goto LPQHi;
        }
        foreach ($groupData as $grp) {
            $this->ion_auth->add_to_group($grp, $id);
        }
        LPQHi:
        xSWMn:
        if ($this->ion_auth->update($user->id, $data)) {
            $this->session->set_flashdata("message", $this->ion_auth->messages());
            $this->redirectUser();
            goto D6AHW;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        $this->redirectUser();
        D6AHW:
        mqv23:
        aeu_X:
        $this->data["csrf"] = $this->_get_csrf_nonce();
        $this->data["message"] = validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata("message"));
        $this->data["user"] = $user;
        $this->data["groups"] = $groups;
        $this->data["currentGroups"] = $currentGroups;
        $this->data["first_name"] = ["name" => "first_name", "id" => "first_name", "type" => "text", "value" => $this->form_validation->set_value("first_name", $user->first_name)];
        $this->data["last_name"] = ["name" => "last_name", "id" => "last_name", "type" => "text", "value" => $this->form_validation->set_value("last_name", $user->last_name)];
        $this->data["company"] = ["name" => "company", "id" => "company", "type" => "text", "value" => $this->form_validation->set_value("company", $user->company)];
        $this->data["phone"] = ["name" => "phone", "id" => "phone", "type" => "text", "value" => $this->form_validation->set_value("phone", $user->phone)];
        $this->data["password"] = ["name" => "password", "id" => "password", "type" => "password"];
        $this->data["password_confirm"] = ["name" => "password_confirm", "id" => "password_confirm", "type" => "password"];
        $this->_render_page("auth/edit_user", $this->data);
    }
    public function create_group()
    {
        $this->data["title"] = $this->lang->line("create_group_title");
        if (!(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())) {
            goto hb2vL;
        }
        redirect("auth", "refresh");
        hb2vL:
        $this->form_validation->set_rules("group_name", $this->lang->line("create_group_validation_name_label"), "trim|required|alpha_dash");
        if (!($this->form_validation->run() === TRUE)) {
            goto ovUb1;
        }
        $new_group_id = $this->ion_auth->create_group($this->input->post("group_name"), $this->input->post("description"));
        if ($new_group_id) {
            $this->session->set_flashdata("message", $this->ion_auth->messages());
            redirect("auth", "refresh");
            goto FqNm6;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        FqNm6:
        ovUb1:
        $this->data["message"] = validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata("message"));
        $this->data["group_name"] = ["name" => "group_name", "id" => "group_name", "type" => "text", "value" => $this->form_validation->set_value("group_name")];
        $this->data["description"] = ["name" => "description", "id" => "description", "type" => "text", "value" => $this->form_validation->set_value("description")];
        $this->_render_page("auth/create_group", $this->data);
    }
    public function edit_group($id)
    {
        if (!(!$id || empty($id))) {
            goto cVtxI;
        }
        redirect("auth", "refresh");
        cVtxI:
        $this->data["title"] = $this->lang->line("edit_group_title");
        if (!(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())) {
            goto VhrN1;
        }
        redirect("auth", "refresh");
        VhrN1:
        $group = $this->ion_auth->group($id)->row();
        $this->form_validation->set_rules("group_name", $this->lang->line("edit_group_validation_name_label"), "trim|required|alpha_dash");
        if (!(isset($_POST) && !empty($_POST))) {
            goto lqXEo;
        }
        if (!($this->form_validation->run() === TRUE)) {
            goto QUlPD;
        }
        $group_update = $this->ion_auth->update_group($id, $_POST["group_name"], array("description" => $_POST["group_description"]));
        if ($group_update) {
            $this->session->set_flashdata("message", $this->lang->line("edit_group_saved"));
            redirect("auth", "refresh");
            goto AOq7_;
        }
        $this->session->set_flashdata("message", $this->ion_auth->errors());
        AOq7_:
        QUlPD:
        lqXEo:
        $this->data["message"] = validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata("message"));
        $this->data["group"] = $group;
        $this->data["group_name"] = ["name" => "group_name", "id" => "group_name", "type" => "text", "value" => $this->form_validation->set_value("group_name", $group->name)];
        if (!($this->config->item("admin_group", "ion_auth") === $group->name)) {
            goto EI3Uy;
        }
        $this->data["group_name"]["readonly"] = "readonly";
        EI3Uy:
        $this->data["group_description"] = ["name" => "group_description", "id" => "group_description", "type" => "text", "value" => $this->form_validation->set_value("group_description", $group->description)];
        $this->_render_page("authDIRECTORY_SEPARATORedit_group", $this->data);
    }
    public function _get_csrf_nonce()
    {
        $this->load->helper("string");
        $key = random_string("alnum", 8);
        $value = random_string("alnum", 20);
        $this->session->set_flashdata("csrfkey", $key);
        $this->session->set_flashdata("csrfvalue", $value);
        return [$key => $value];
    }
    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata("csrfkey"));
        if (!($csrfkey && $csrfkey === $this->session->flashdata("csrfvalue"))) {
            return false;
        }
        return true;
    }
    public function _render_page($view, $data = NULL, $returnhtml = FALSE)
    {
        $viewdata = empty($data) ? $this->data : $data;
        $view_html = $this->load->view($view, $viewdata, $returnhtml);
        if (!$returnhtml) {
            // [PHPDeobfuscator] Implied return
            return;
        }
        return $view_html;
    }
}