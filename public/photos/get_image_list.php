<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

$department = $_POST['department'];

$image_extensions = ['jpg', 'png'];
$filenames = [];
$files = scandir($department);
foreach ($files as $filename) {
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), $image_extensions)) {
        $filenames[] = $filename;
    }

}

echo json_encode($filenames);
