<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/private/functions/f_get_amazon.php';
require_once $CONFIG['include'];
checkLogin();
require_once($CONFIG['header']);

$api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
echo "<div class=pagebox >\n";


if (isset($_POST['sku'])) {
    $sku = trim($_POST['sku'], $api);
    $item = get_item($sku);
} elseif (isset($_GET['sku'])) {
    $sku = trim($_GET['sku']);
    $item = get_item($sku, $api);
}

if (!(isset($sku))) {
    $sku = '';
}

echo_search_form($sku);

if (!isset($item)) {
    exit();
}

echo_barcodes($item);
echo_vital_info($item, $api);
if ($item -> has_variations == true) {
    echo_variations($item, $api);
} else {
    echo_offer($item, $api);
}
echo_images($item, $api);
echo_description($item, $api);
echo "</div>";
echo_scripts();

include($CONFIG['footer']);
