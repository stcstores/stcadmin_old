<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

$order_data = $api->get_open_orders();

$orders = array();
foreach ($order_data as $order_datas) {
    $orders[] = new STCAdmin\Dispatch\OpenOrder($order_datas);
}
echo "<table>";
echo "<tr>";
echo "<td>" . 'Id' . "</td>";
echo "<td>" . 'GUID' . "</td>";
echo "<td>" . 'Custmer' . "</td>";
echo "<td>" . 'Is Printed' . "</td>";
echo "<td>" . 'Postage Service' . "</td>";
echo "<td>" . 'Department' . "</td>";
echo "<td>" . 'Item Count' . "</td>";
echo "</tr>";
foreach ($orders as $order) {
    echo "<tr>";
    echo "<td>" . $order->order_number . "</td>";
    echo "<td>" . $order->guid . "</td>";
    echo "<td>" . $order->customer_name . "</td>";
    echo "<td>" . $order->printed . "</td>";
    echo "<td>" . $order->postage_service . "</td>";
    echo "<td>" . $order->department . "</td>";
    echo "<td>" . count($order->items) . "</td>";
    echo "</tr>";
}

include($CONFIG['footer']);
