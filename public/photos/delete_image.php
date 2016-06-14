<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

$department = $_POST['department'];
$filename = $_POST['filename'];
$file = realpath('.') . '/' . $department . '/' . $filename;
unlink($file);
