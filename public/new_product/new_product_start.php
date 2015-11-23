<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
session_start();
STCAdmin\UserLogin::checkLogin();
unset($_SESSION['new_product']);
header('Location: new_linnworks_product_1_basic_info.php');
exit();
?>
