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
<br />
<label for="department_select">Department</label>
<select id="department_select" name="department_select">
<?php
foreach ($departments as $department) {
    echo "\t<option value='" . $department . "' >" . $department . "</option>" . $department . "\n";
}
?>
</select>
<label for="filter_order_number">Filter: Order Number</label>
<input type="text" name="filter_order_number" id="filter_order_number" class="filter_input"/>
<label for="filter_customer_name">Filter: Customer Name</label>
<input type="text" name="filter_customer_name" id="filter_customer_name" class="filter_input"/>
<button id="clear_filters">Clear</button>
<br />
<button id="process_selected">Process Selected</button>
<br />
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
