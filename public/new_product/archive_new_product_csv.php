<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);


$time = time();
$year = date('Y', $time);
$month = date('m', $time);
$day = date('d', $time);

$date = $year . '-' . $month . '-' . $day;
$folderName = $date . '-' . $time;

mkdir($OLDCSVFILEPATH . $folderName);

foreach (scandir($CSVFILEPATH) as $filename) {
    if (!in_array($filename, array("..", ".", "", ".gitignore"))) {
        rename($CSVFILEPATH . $filename, $OLDCSVFILEPATH . $folderName . '/' . $filename);
    }
}

$zip = new ZipArchive();
$zip->open($OLDCSVFILEPATH . $folderName . '/New_Linnworks_Products-' . $time . '.zip', ZipArchive::CREATE);

foreach (scandir($OLDCSVFILEPATH . $folderName) as $filename) {
    if (!in_array($filename, array("..", ".", ""))) {
        $zip->addFile($OLDCSVFILEPATH . $folderName . '/' . $filename, $filename);
    }
}

$zip->close();
