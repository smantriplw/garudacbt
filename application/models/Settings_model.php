<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Settings_model extends CI_Model
{
    public function not_admin()
    {
        $this->db->select("a.id");
        $this->db->from("users a");
        $this->db->join("users_groups b", "a.id=b.user_id");
        $this->db->where_not_in("b.group_id", ["1"]);
        return $this->db->get()->result();
    }
    public function truncate($table)
    {
        $this->load->helper("file");
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        foreach ($table as $tb) {
            $this->db->truncate($tb);
        }
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
        delete_files("./uploads/bank_soal/");
        $users = $this->not_admin();
        foreach ($users as $user) {
            $this->db->delete("users", array("id" => $user->id));
        }
        return;
    }
    public function getSetting()
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        return $cached = $this->db->get("setting")->row();
    }
    /**
     * Background image for the login screen is stored on the setting row. The
     * column is added on demand so existing databases keep working.
     */
    public function ensureSchema()
    {
        if (schema_has_column("setting", "login_bg")) {
            return;
        }
        $this->load->dbforge();
        $this->dbforge->add_column("setting", [
            "login_bg" => ["type" => "VARCHAR", "constraint" => 255, "null" => TRUE, "default" => NULL],
        ]);
    }
    function toJSON($table)
    {
        $query = $this->db->get($table);
        return json_encode($query->result(), JSON_PRETTY_PRINT);
    }
    function rowSize($table)
    {
        return $this->db->count_all($table);
    }
}
