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
     * Background images for the login screen live in their own table: the
     * setting table uses ROW_FORMAT=COMPACT and is already at the row size
     * limit, so another TEXT column is rejected with "row size too large".
     */
    public function ensureSchema()
    {
        if ($this->db->table_exists("setting_login_bg")) {
            return;
        }
        $this->db->query("CREATE TABLE IF NOT EXISTS `setting_login_bg` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `path` VARCHAR(255) NOT NULL,
            `created_at` DATETIME NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function loginBackgrounds($setting = null)
    {
        $list = [];
        if ($this->db->table_exists("setting_login_bg")) {
            $rows = $this->db->select("path")->from("setting_login_bg")->order_by("id", "ASC")->get()->result();
            foreach ($rows as $row) {
                if (trim((string) $row->path) !== "") {
                    $list[] = $row->path;
                }
            }
        }
        if (count($list) === 0 && $setting != null && !empty($setting->login_bg)) {
            $list[] = (string) $setting->login_bg;
        }
        return $list;
    }

    public function addLoginBackground($path)
    {
        $this->ensureSchema();
        return $this->db->insert("setting_login_bg", ["path" => $path, "created_at" => date("Y-m-d H:i:s")]);
    }

    public function removeLoginBackground($path)
    {
        if (!$this->db->table_exists("setting_login_bg")) {
            return false;
        }
        $this->db->where("path", $path)->delete("setting_login_bg");
        return true;
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
