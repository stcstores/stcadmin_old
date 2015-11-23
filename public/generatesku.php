<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

echo $api->get_new_sku();

?>
