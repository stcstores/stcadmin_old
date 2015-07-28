<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();

$api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);

echo $api->getNewSKU();

?>