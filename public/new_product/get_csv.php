<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
    
    
$time = time();
$folderName = date("Y-m-d",$time) . '-' . $time;
echo $folderName;
echo "<br />";

mkdir($OLDCSVFILEPATH . $folderName);

foreach (scandir($CSVFILEPATH) as $filename) {
    if (!in_array($filename, array("..", ".", ""))){
        rename($CSVFILEPATH . $filename, $OLDCSVFILEPATH . $folderName . '/' . $filename);
    }
}

$zip = new ZipArchive();
echo $OLDCSVFILEPATH . $folderName . '/New_Linnworks_Products-' . $time . '.zip';
echo "<br />";
$zip->open($OLDCSVFILEPATH . $folderName . '/New_Linnworks_Products-' . $time . '.zip', ZipArchive::CREATE);

foreach (scandir($OLDCSVFILEPATH . $folderName) as $filename) {
    if (!in_array($filename, array("..", ".", ""))){
        $zip->addFile($OLDCSVFILEPATH . $folderName . '/' . $filename, $filename);
        echo $OLDCSVFILEPATH . $folderName . '/' . $filename;
        echo "<br />";
    }
}

$zip->close();