<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

if (isset($_POST['order_number'])) {
    $response = $api->get_open_order_GUID_by_number($_POST['order_number']);
    if ($response == '') {
        echo 'success';
    } else {
        echo 'fail';
    }
} else {
    echo "No order ID";
}
