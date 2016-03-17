<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

if (isset($_POST['location_update'])) {
    $update_info = json_decode($_POST['location_update'], true);
    foreach ($update_info as $update) {
        if (strlen($update['binrack_value']) > 0) {
            $title = strtoupper($update['binrack_value']) . ' ' . $update['title'];
            $api->update_category($update['item_id'], 'Sureware');
        } else {
            $title = $update['title'];
        }
        $api->updateTitle($update['item_id'], $title);
    }

} else {
    echo "ERROR";
}
