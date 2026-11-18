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
$login_bgs = isset($login_bgs) && is_array($login_bgs) ? $login_bgs : [];
if (count($login_bgs) === 0 && isset($setting->login_bg) && trim((string) $setting->login_bg) !== "") {
    $login_bgs[] = trim((string) $setting->login_bg);
}
?>
<style>
    html { background: #343a40; min-height: 100%; }
    body { background: transparent; min-height: 100vh; }
    .login-bg-layer { position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: -1; overflow: hidden; background: #111; }
    .login-bg-slide { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-position: center; background-size: cover; background-repeat: no-repeat; opacity: 0; transition: opacity 1s ease-in-out; }
    .login-bg-slide.active { opacity: 1; }
    .login-bg-shade { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, .45); }
</style>
</head>
<body class="hold-transition">
<?php if (count($login_bgs) > 0): ?>
    <div class="login-bg-layer" aria-hidden="true">
        <?php foreach ($login_bgs as $i => $bg): ?>
            <?php
            $bg_path = FCPATH . ltrim($bg, "/");
            $bg_url = base_url() . ltrim($bg, "/");
            if (is_file($bg_path)) {
                $bg_url .= "?v=" . filemtime($bg_path);
            }
            ?>
            <div class="login-bg-slide<?= $i === 0 ? " active" : "" ?>"
                 style="background-image:url(<?= html_escape($bg_url) ?>)"></div>
        <?php endforeach; ?>
        <div class="login-bg-shade"></div>
    </div>
<?php endif; ?>
