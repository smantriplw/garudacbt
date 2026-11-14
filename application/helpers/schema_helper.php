<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('schema_has_column')) {
    /**
     * field_exists() memoised per request. The bare check issues a
     * SHOW COLUMNS query, and several pages ran it on every load.
     */
    function schema_has_column($table, $column)
    {
        static $cache = [];
        $key = $table . '.' . $column;
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }
        $CI =& get_instance();
        return $cache[$key] = $CI->db->field_exists($column, $table);
    }
}

if (!function_exists('schema_column_type')) {
    /**
     * Declared column type (lowercase, e.g. "varchar"), or NULL when absent.
     */
    function schema_column_type($table, $column)
    {
        static $cache = [];
        $key = $table . '.' . $column;
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }
        $CI =& get_instance();
        $CI->db->select('COLUMN_TYPE', false);
        $CI->db->from('information_schema.COLUMNS');
        $CI->db->where('TABLE_SCHEMA', 'DATABASE()', false);
        $CI->db->where('TABLE_NAME', $table);
        $CI->db->where('COLUMN_NAME', $column);
        $row = $CI->db->get()->row();
        return $cache[$key] = $row === null ? null : strtolower($row->COLUMN_TYPE);
    }
}

if (!function_exists('schema_table_count')) {
    /**
     * Number of tables in the current schema, memoised per request.
     */
    function schema_table_count()
    {
        static $count = null;
        if ($count !== null) {
            return $count;
        }
        $CI =& get_instance();
        return $count = count($CI->db->list_tables());
    }
}

if (!function_exists('schema_tables_with_columns')) {
    /**
     * Tables that own any of the given columns, in one information_schema
     * query instead of list_tables() plus field_data() per table.
     */
    function schema_tables_with_columns(array $columns)
    {
        $ret = [];
        if (count($columns) === 0) {
            return $ret;
        }
        $CI =& get_instance();
        $CI->db->select('TABLE_NAME, COLUMN_NAME', false);
        $CI->db->from('information_schema.COLUMNS');
        $CI->db->where('TABLE_SCHEMA', 'DATABASE()', false);
        $CI->db->where_in('COLUMN_NAME', $columns);
        foreach ($CI->db->get()->result() as $row) {
            $ret[$row->TABLE_NAME][$row->COLUMN_NAME] = true;
        }
        return $ret;
    }
}
