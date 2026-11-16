<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('footer_setting')) {
    function footer_setting()
    {
        static $footer = null;
        if ($footer === null) {
            $CI =& get_instance();
            $CI->load->model('Settings_model', 'footer_settings');
            $footer = $CI->footer_settings->footerSetting();
        }
        return $footer;
    }
}
