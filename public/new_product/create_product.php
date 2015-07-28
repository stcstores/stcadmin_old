<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();

if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    echo "NO PRODUCT";
    exit();
}

$api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);

$guid = createGUID()

function getCreateInventoryItem($product, $guid) {
    $item = array();
    $item['ItemNumber'] = $product->details['sku']->text;
    $item['ItemTitle'] = $product->details['text']->text;
    $item['BarcodeNumber'] = $product->details['barcode']->text;
    $item['PurchasePrice'] = $product->details['purchase_price']->text;
    $item['RetailPrice'] = $product->details['retail_price']->text;
    $item['Quantity'] = $product->details['quantity']->text;
    $item['TaxRate'] = '';
    $item['StockItemId'] = $guid;
    return $item;
}

