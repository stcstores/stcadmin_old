<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);
$database = new STCAdmin\Database();

$itemList = $api->search_inventory_item_title('FW799');

#print_r($itemList);


include($CONFIG['footer']);
