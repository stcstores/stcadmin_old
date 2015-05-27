<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');

$archiveFolder = $CONFIG['archive'];

$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$time = $_GET['time'];


$file = $archiveFolder . $year . '-' . $month . '-' . $day . '-' . $time . '/New_Linnworks_Products-' . $time . '.zip';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
} else {
    echo 'error';
}
?>