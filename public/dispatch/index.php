<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

$order_data = $api->get_open_orders();
$orders = array();
foreach ($order_data as $order) {
    $orders[] = new STCAdmin\Dispatch\OpenOrder($order);
}
$printedOrders = array();
foreach ($orders as $order) {
    if ($order->printed) {
        $printedOrders[] = $order;
    }
}

$departments = array('');
foreach ($orders as $order) {
    if (!(in_array($order->department, $departments))) {
        $departments[] = $order->department;
    }
}

echo "<script>openOrders = " . json_encode($printedOrders) . ";</script>\n";
?>
<button id="reload">Refresh</button>
<label for="department_select">Department</label>
<select id="department_select" name="department_select">
<?php
foreach ($departments as $department) {
    echo "\t<option value='" . $department . "' >" . $department . "</option>" . $department . "\n";
}
?>
</select>
<table id="order_table" class="order_table" cellspacing="0">
    <tr>
        <th>Process</th>
        <th></th>
        <th>Order Number</th>
        <th>Customer Name</th>
        <th>Items</th>
    </tr>
</table>
<script src="/scripts/dispatch.js"></script>
<?php
include($CONFIG['footer']);
