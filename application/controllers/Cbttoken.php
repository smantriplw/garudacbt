<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbttoken extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto TLsuY;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto QRGV6;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        QRGV6:
        TLsuY:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Log_model", "logging");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto nWcw0;
        }
        $data = json_encode($data);
        nWcw0:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Token Ujian", "subjudul" => "Token", "setting" => $this->dashboard->getSetting()];
        $tp = $this->master->getTahunActive();
        $smt = $this->master->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $token = $this->cbt->getToken();
        $tkn["token"] = '';
        $tkn["auto"] = "0";
        $tkn["jarak"] = "1";
        $tkn["elapsed"] = "00:00:00";
        $data["token"] = $token != null ? $token : json_decode(json_encode($tkn));
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("cbt/token/data");
            $this->load->view("_templates/dashboard/_footer");
            goto A_8fN;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $data["guru"] = $guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("members/guru/cbt/token/data");
        $this->load->view("members/guru/templates/footer");
        A_8fN:
    }
    public function generateToken()
    {
        $post_token = json_decode($this->input->get("data"));
        $force = $this->input->get("force");
        $token = $this->cbt->getToken();
        $updated = date("Y-m-d H:i:s");
        if ($force == "1") {
            $new = $this->createNewToken();
            $post_token->token = $new;
            $post_token->updated = $updated;
            $this->cbt->saveToken($post_token);
            goto Ms523;
        }
        $mulai = new DateTime($token->updated);
        $diff = $mulai->diff(new DateTime());
        $total_minutes = $diff->days * 24 * 60;
        $total_minutes += $diff->h * 60;
        $total_minutes += $diff->i;
        if (!($total_minutes >= $post_token->jarak)) {
            goto VTQnX;
        }
        $new = $this->createNewToken();
        $post_token->token = $new;
        $post_token->updated = $updated;
        $this->cbt->saveToken($post_token);
        VTQnX:
        Ms523:
        $token = $this->cbt->getToken();
        $token->now = $updated;
        $this->output_json($token);
    }
    public function loadToken()
    {
        $dataflds = $this->db->field_data("cbt_token");
        $table_changed = false;
        foreach ($dataflds as $fild) {
            if (!($fild->name == "updated")) {
                goto hPJ4p;
            }
            if (!($fild->type != "varchar")) {
                goto moL0y;
            }
            $field = ["updated" => array("type" => "VARCHAR", "constraint" => 20, "default" => '')];
            $table_changed = $this->dbforge->modify_column("cbt_token", $field);
            moL0y:
            hPJ4p:
        }
        $token = $this->cbt->getToken();
        if ($token == null) {
            $data["token"] = '';
            $data["auto"] = "0";
            $data["elapsed"] = "00:00:00";
            $this->output_json($data);
            goto ObnF_;
        }
        $token->now = date("Y-m-d H:i:s");
        $this->output_json($token);
        ObnF_:
    }
    private function createNewToken()
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $input_length = strlen($chars);
        $new_token = '';
        $i = 0;
        YLdD4:
        if (!($i < 6)) {
            return $new_token;
        }
        $random_character = $chars[mt_rand(0, $input_length - 1)];
        $new_token .= $random_character;
        $i++;
        goto YLdD4;
    }
}
