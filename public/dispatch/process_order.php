<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

if (isset($_POST['order_number'])) {
    $response = $api->process_order_by_order_number($_POST['order_number']);
    if ($response == '') {
        echo $_POST['order_number'] . ' processed';
    } else {
        echo $_POST['order_number']. ' failed';
    }
} else {
    echo "No order ID";
}
