<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Page Not Found</title>
    <style type="text/css">
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: #f4f6f8;
            font: 14px/1.6 Helvetica, Arial, sans-serif;
            color: #4F5155;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #container {
            max-width: 480px;
            width: 90%;
            margin: 10px;
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 40px 30px;
        }
        h1 {
            color: #e13300;
            font-size: 54px;
            line-height: 1;
            margin-bottom: 16px;
        }
        h2 { font-size: 20px; color: #333; margin-bottom: 12px; font-weight: 600; }
        p { color: #6b7480; margin-bottom: 20px; }
        a {
            display: inline-block;
            color: #fff;
            background-color: #003399;
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 4px;
            font-weight: 600;
        }
        a:hover { background-color: #002a7e; }
    </style>
</head>
<body>
<div id="container">
    <h1>404</h1>
    <h2>Halaman tidak ditemukan</h2>
    <p>Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
    <a href="<?php echo base_url(); ?>">Kembali ke Beranda</a>
</div>
</body>
</html>