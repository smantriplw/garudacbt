<?php

if (defined("BASEPATH")) {
    class Compare extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->CHARACTER_SET = "utf8 COLLATE utf8_general_ci";
            $this->DB1 = $this->load->database("main_garuda", TRUE);
            $this->DB2 = $this->load->database("live", TRUE);
        }
        function index()
        {
            $sql_commands_to_run = array();
            $development_tables = $this->DB1->list_tables();
            $live_tables = $this->DB2->list_tables();
            $tables_to_create = array_diff($development_tables, $live_tables);
            $tables_to_drop = array_diff($live_tables, $development_tables);
            $sql_commands_to_run = is_array($tables_to_create) && !empty($tables_to_create) ? array_merge($sql_commands_to_run, $this->manage_tables($tables_to_create, "create")) : array();
            $sql_commands_to_run = is_array($tables_to_drop) && !empty($tables_to_drop) ? array_merge($sql_commands_to_run, $this->manage_tables($tables_to_drop, "drop")) : array();
            $tables_to_update = $this->compare_table_structures($development_tables, $live_tables);
            $tables_to_update = array_diff($tables_to_update, $tables_to_create);
            $sql_commands_to_run = is_array($tables_to_update) && !empty($tables_to_update) ? array_merge($sql_commands_to_run, $this->update_existing_tables($tables_to_update)) : '';
            if (is_array($sql_commands_to_run) && !empty($sql_commands_to_run)) {
                echo "<h2>The database is out of Sync!</h2>\n";
                echo "<p>The following SQL commands need to be executed to bring the Live database tables up to date: </p>\n";
                echo "<pre style='padding: 20px; background-color: #FFFAF0;'>\n";
                foreach ($sql_commands_to_run as $sql_command) {
                    echo "{$sql_command}\n";
                }
                echo "<pre>\n";
                goto aYjg5;
            }
            echo "<h2>The database appears to be up to date</h2>\n";
            aYjg5:
        }
        function manage_tables($tables, $action)
        {
            $sql_commands_to_run = array();
            if (!($action == "create")) {
                goto HS78K;
            }
            foreach ($tables as $table) {
                $query = $this->DB1->query("SHOW CREATE TABLE `{$table}` -- create tables");
                $table_structure = $query->row_array();
                $sql_commands_to_run[] = $table_structure["Create Table"] . ";";
            }
            HS78K:
            if (!($action == "drop")) {
                goto rYRyV;
            }
            foreach ($tables as $table) {
                $sql_commands_to_run[] = "DROP TABLE {$table};";
            }
            rYRyV:
            return $sql_commands_to_run;
        }
        function compare_table_structures($development_tables, $live_tables)
        {
            $tables_need_updating = array();
            $live_table_structures = $development_table_structures = array();
            foreach ($development_tables as $table) {
                $query = $this->DB1->query("SHOW CREATE TABLE `{$table}` -- dev");
                $table_structure = $query->row_array();
                $development_table_structures[$table] = $table_structure["Create Table"];
            }
            foreach ($live_tables as $table) {
                $query = $this->DB2->query("SHOW CREATE TABLE `{$table}` -- live");
                $table_structure = $query->row_array();
                $live_table_structures[$table] = $table_structure["Create Table"];
            }
            foreach ($development_tables as $table) {
                $development_table = $development_table_structures[$table];
                $live_table = isset($live_table_structures[$table]) ? $live_table_structures[$table] : '';
                if (!($this->count_differences($development_table, $live_table) > 0)) {
                    goto yuv_3;
                }
                $tables_need_updating[] = $table;
                yuv_3:
            }
            return $tables_need_updating;
        }
        function count_differences($old, $new)
        {
            $differences = 0;
            $old = trim(preg_replace("/\\s+/", '', $old));
            $new = trim(preg_replace("/\\s+/", '', $new));
            if (!($old == $new)) {
                $old = explode(" ", $old);
                $new = explode(" ", $new);
                $length = max(count($old), count($new));
                $i = 0;
                YfvXC:
                if (!($i < $length)) {
                    return $differences;
                }
                if (!($old[$i] != $new[$i])) {
                    goto GIsMZ;
                }
                $differences++;
                GIsMZ:
                $i++;
                goto YfvXC;
            }
            return $differences;
        }
        function update_existing_tables($tables)
        {
            $sql_commands_to_run = array();
            $table_structure_development = array();
            $table_structure_live = array();
            if (!(is_array($tables) && !empty($tables))) {
                goto ZV1um;
            }
            foreach ($tables as $table) {
                $table_structure_development[$table] = $this->table_field_data((array) $this->DB1, $table);
                $table_structure_live[$table] = $this->table_field_data((array) $this->DB2, $table);
            }
            ZV1um:
            $sql_commands_to_run = array_merge($sql_commands_to_run, $this->determine_field_changes($table_structure_development, $table_structure_live));
            return $sql_commands_to_run;
        }
        function table_field_data($database, $table)
        {
            $conn = mysqli_connect($database["hostname"], $database["username"], $database["password"]);
            mysql_select_db($database["database"]);
            $result = mysql_query("SHOW COLUMNS FROM `{$table}`");
            MVaVW:
            if (!($row = mysql_fetch_assoc($result))) {
                return $fields;
            }
            $fields[] = $row;
            goto MVaVW;
        }
        function determine_field_changes($source_field_structures, $destination_field_structures)
        {
            $sql_commands_to_run = array();
            foreach ($source_field_structures as $table => $fields) {
                foreach ($fields as $field) {
                    if ($this->in_array_recursive($field["Field"], $destination_field_structures[$table])) {
                        $modify_field = '';
                        $n = 0;
                        eVphq:
                        if (!($n < count($fields))) {
                            goto gwWO2;
                        }
                        if (!(isset($fields[$n]) && isset($destination_field_structures[$table][$n]) && $fields[$n]["Field"] == $destination_field_structures[$table][$n]["Field"])) {
                            goto yZcWV;
                        }
                        $differences = array_diff($fields[$n], $destination_field_structures[$table][$n]);
                        if (!(is_array($differences) && !empty($differences))) {
                            goto cM9no;
                        }
                        $modify_field = "ALTER TABLE {$table} MODIFY COLUMN `" . $fields[$n]["Field"] . "` " . $fields[$n]["Type"] . " CHARACTER SET " . $this->CHARACTER_SET;
                        $modify_field .= isset($fields[$n]["Default"]) && $fields[$n]["Default"] != '' ? " DEFAULT '" . $fields[$n]["Default"] . "'" : '';
                        $modify_field .= isset($fields[$n]["Null"]) && $fields[$n]["Null"] == "YES" ? " NULL" : " NOT NULL";
                        $modify_field .= isset($fields[$n]["Extra"]) && $fields[$n]["Extra"] != '' ? " " . $fields[$n]["Extra"] : '';
                        $modify_field .= isset($previous_field) && $previous_field != '' ? " AFTER " . $previous_field : '';
                        $modify_field .= ";";
                        cM9no:
                        $previous_field = $fields[$n]["Field"];
                        yZcWV:
                        if (!($modify_field != '' && !in_array($modify_field, $sql_commands_to_run))) {
                            goto lLpf4;
                        }
                        $sql_commands_to_run[] = $modify_field;
                        lLpf4:
                        $n++;
                        goto eVphq;
                    }
                    $add_field = "ALTER TABLE {$table} ADD COLUMN `" . $field["Field"] . "` " . $field["Type"] . " CHARACTER SET " . $this->CHARACTER_SET;
                    $add_field .= isset($field["Null"]) && $field["Null"] == "YES" ? " Null" : '';
                    $add_field .= " DEFAULT " . $field["Default"];
                    $add_field .= isset($field["Extra"]) && $field["Extra"] != '' ? " " . $field["Extra"] : '';
                    $add_field .= ";";
                    $sql_commands_to_run[] = $add_field;
                    gwWO2:
                }
            }
            return $sql_commands_to_run;
        }
        function in_array_recursive($needle, $haystack, $strict = false)
        {
            foreach ($haystack as $array => $item) {
                $item = $item["Field"];
                if (!(($strict ? $item === $needle : $item == $needle) || is_array($item) && in_array_recursive($needle, $item, $strict))) {
                }
                return true;
            }
            return false;
        }
    }
    // [PHPDeobfuscator] Implied script end
    return;
}
exit("No direct script access allowed");
