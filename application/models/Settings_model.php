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
        if (!$this->db->table_exists("setting_login_bg")) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `setting_login_bg` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `path` VARCHAR(255) NOT NULL,
                `created_at` DATETIME NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
        if (!$this->db->table_exists("setting_footer")) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `setting_footer` (
                `id` TINYINT(4) NOT NULL,
                `brand` VARCHAR(191) NULL,
                `copyright` VARCHAR(191) NULL,
                `login_text` VARCHAR(191) NULL,
                `updated_at` DATETIME NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }

    public function footerSetting()
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $setting = $this->getSetting();
        $brand = $setting != null && isset($setting->sekolah) ? trim((string) $setting->sekolah) : "";
        $copyright = "Copyright © 2014-2019 AdminLTE.io.";
        $login = "";
        if ($this->db->table_exists("setting_footer")) {
            $row = $this->db->where("id", 1)->get("setting_footer")->row();
            if ($row !== null) {
                $brand = isset($row->brand) ? trim((string) $row->brand) : $brand;
                $copyright = isset($row->copyright) ? trim((string) $row->copyright) : $copyright;
                $login = isset($row->login_text) ? trim((string) $row->login_text) : "";
            }
        }
        return $cached = ["brand" => $brand, "copyright" => $copyright, "login_text" => $login];
    }

    public function saveFooter(array $footer)
    {
        $this->ensureSchema();
        $payload = [
            "brand" => (string) $footer["brand"],
            "copyright" => (string) $footer["copyright"],
            "login_text" => (string) $footer["login_text"],
            "updated_at" => date("Y-m-d H:i:s"),
        ];
        if ($this->db->where("id", 1)->get("setting_footer")->row() !== null) {
            return $this->db->where("id", 1)->update("setting_footer", $payload);
        }
        $payload["id"] = 1;
        return $this->db->insert("setting_footer", $payload);
    }

    public function loginBackgrounds($setting = null, $fallback = false)
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
        if (count($list) === 0 && $fallback) {
            foreach (["assets/img/smanti.png", "assets/img/smanti2.png", "assets/img/smanti3.png"] as $candidate) {
                if (is_file(FCPATH . $candidate)) {
                    $list[] = $candidate;
                }
            }
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
