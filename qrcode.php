<?php
// 14.10.2019, rk1: if $config['rk_minimal'] given and true then do nothing with the photo except displaing it (no thumbnail, qr-code or anything esle)	

$my_config = 'my.config.inc.php';
if (file_exists($my_config)) {
	require_once($my_config);
} else {
	require_once('config.inc.php');
}

if ((isset($config['rk_minimal']) ? $config['rk_minimal'] : false) == false) {
    $filename = $_GET['filename'];
    include('resources/lib/phpqrcode/qrlib.php');
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/download.php?image=';
    QRcode::png($url.$filename, false, QR_ECLEVEL_H, 10);
}