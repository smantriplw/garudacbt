<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php $logo_app = $setting->logo_kiri == null ? base_url() . 'assets/img/favicon.png' : base_url() . $setting->logo_kiri; ?>
    <link rel="shortcut icon" href="<?= $logo_app ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/app/css/mystyle.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?= base_url() ?>/assets/plugins/jquery/jquery.min.js"></script>
</head>
<?php
$login_bg = isset($setting->login_bg) ? trim((string) $setting->login_bg) : "";
$login_bg_style = "background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);";
if ($login_bg !== "") {
    $login_bg_path = FCPATH . ltrim($login_bg, "/");
    $login_bg_url = base_url() . ltrim($login_bg, "/");
    if (is_file($login_bg_path)) {
        $login_bg_url .= "?v=" . filemtime($login_bg_path);
    }
    $login_bg_style = "background: linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)), url(" . $login_bg_url . ") center center / cover no-repeat fixed;";
}
?>
<body class="hold-transition" style="<?= html_escape($login_bg_style) ?>">
