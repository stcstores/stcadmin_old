<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

echo '<script src="/scripts/jquery.tablesorter.js" ></script>' . "\n";

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
<div class="dispatcher" id="dispatcher">
    <table class="dispatcher_controls">
        <tr>
            <td class="label"><label for="department_select">Department</label></td>
            <td class="input">
                <select id="department_select" name="department_select">
                <?php
                foreach ($departments as $department) {
                    echo "\t<option value='" . $department . "' >" . $department . "</option>" . $department . "\n";
                }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label"><label for="filter_order_number">Filter: Order Number</label></td>
            <td class="input"><input type="text" name="filter_order_number" id="filter_order_number" class="filter_input"/></td>
        </tr>
        <tr>
            <td class="label"><label for="filter_customer_name">Filter: Customer Name</label></td>
            <td class="input"><input type="text" name="filter_customer_name" id="filter_customer_name" class="filter_input"/></td>
        </tr>
        <tr>
            <td><button id="reload">Refresh</button></td>
            <td><button id="clear_filters">Clear Filters</button></td>
        </tr>
        <tr>
            <td><span id="selected_count">0</span> of <span id="order_count">0</span> Selected</td>
            <td><button id="process_selected">Process Selected</button></td>
        </tr>
    </table>
    <br />
    </table>
</div>
<script src="/scripts/dispatch.js"></script>
<?php
include($CONFIG['footer']);
