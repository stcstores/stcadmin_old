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

function getCreateInventoryItem($product) {
    $item = array();
    $item['ItemNumber'] = (string)$product->details['sku']->text;
    $item['ItemTitle'] = (string)$product->details['item_title']->text;
    $item['BarcodeNumber'] = (string)$product->details['barcode']->text;
    $item['PurchasePrice'] = (string)$product->details['purchase_price']->text;
    $item['RetailPrice'] = (string)$product->details['retail_price']->text;
    $item['Quantity'] = '0';
    $item['TaxRate'] = '0';
    $item['StockItemId'] = (string)$product->details['guid']->text;
    return $item;
}

function getCategoryId($api, $categoryName) {
    $categoryInfo = $api->getCategoryInfo();
    foreach ($categoryInfo as $category) {
        if ($category['name'] == $categoryName) {
            return $category['id'];
        }
    }
    // ERROR HANDLE
}

function getPackageId($api, $groupName) {
    $packageInfo = $api->getPackagingGroupInfo();
    foreach ($packageInfo as $group) {
        if ($group['name'] == $groupName) {
            return $group['id'];
        }
    }
    // ERROR HANDLE
}

function getServiceId($api) {
    $serviceInfo = $api->getShippingMethodInfo();
    return $serviceInfo[0]['id'];
}

function getUpdateInventoryItem($api, $product) {
    $item = array();
    $item['ItemNumber'] = (string)$product->details['sku']->text;
    $item['ItemTitle'] = (string)$product->details['item_title']->text;
    $item['BarcodeNumber'] = (string)$product->details['barcode']->text;
    $item['PurchasePrice'] = (string)$product->details['purchase_price']->text;
    $item['RetailPrice'] = (string)$product->details['retail_price']->text;
    $item['Quantity'] = '0';
    $item['TaxRate'] = '0';
    $item['StockItemId'] = (string)$product->details['guid']->text;
    $item['VariationGroupName'] = '';
    $item['MetaData'] = 'short_description';
    $item['CategoryId'] = getCategoryId($api, $product->details['department']->text);
    $item['PackageGroupId'] = getPackageId($api, $product->details['shipping_method']->text);
    $item['PostalServiceId'] = getServiceId($api);
    $item['Weight'] = (string)$product->details['weight']->text;
    $item['Width'] = (string)$product->details['width']->text;
    $item['Depth'] = (string)$product->details['depth']->text;
    $item['Height'] = (string)$product->details['height']->text;
    return $item;
}

function createItem($api, $product) {
    $url = $api -> server . '/api/Inventory/AddInventoryItem';
    $dataArray = getCreateInventoryItem($product);
    $dataJson = json_encode($dataArray);
    $data = array('inventoryItem' => $dataJson);
    print_r($api -> request($url, $data));
}

function updateItem($api, $product) {
    $url = $api -> server . '/api/Inventory/UpdateInventoryItem';
    $dataArray = getUpdateInventoryItem($api, $product);
    $dataJson = json_encode($dataArray);
    $data = array('inventoryItem' => $dataJson);
    print_r($api -> request($url, $data));
}

function createExtendedProperties($api, $product) {
    $url = $api -> server . '/api/Inventory/CreateInventoryItemExtendedProperties';
    $dataArray = getExtendedPropertiesArray($product);
    print_r($dataArray);
    echo "<br />";
    $dataJson = json_encode($dataArray);
    //str_replace(array('\n'), '', $dataJson);
    echo "<br />";
    echo $dataJson;
    echo "<br />";
    $data = array('inventoryItemExtendedProperties' => $dataJson);
    print_r($api -> request($url, $data));
}

function createExtendedProperty($product, $name, $value, $type) {
    $exProp = array();
    $exProp['pkRowId'] = createGUID();
    $exProp['fkStockItemId'] = (string)$product->details['guid']->text;
    $exProp['ProperyName'] = $name;
    $exProp['PropertyValue'] = $value;
    $exProp['PropertyType'] = $type;
    return $exProp;
}

function getExtendedPropertiesArray($product) {
    $extendedProperties = array();
    $properties = array(
        array('Manufacturer', (string)$product->details['manufacturer']->text, 'Attribute'),
        array('Brand', (string)$product->details['brand']->text, 'Attribute'),
        array('Colour', (string)$product->details['colour']->text, 'Attribute'),
        array('Material', (string)$product->details['material']->text, 'Attribute'),
        array('Style', (string)$product->details['style']->text, 'Attribute'),
        array('InternationalShipping', (string)$product->details['int_shipping']->text, 'Attribute'),
    );
    foreach ($properties as $prop) {
        $exProp = createExtendedProperty($product, $prop[0], $prop[1], $prop[2]);
        $extendedProperties[] = $exProp;
    }
    return $extendedProperties;
}

function getImageUploadArray($product) {
    $sku = $product->details['sku']->text;
    $url = $api->server . '/api/Uploader/UploadFileFromUrl'
    $imageInfo = getImageIdsForSKU($sku);
    foreach ($imageInfo as $image) {
        $imageUrl = $IMAGEURLPATH . $image['id'];
        $data = array();
        $data['type'] = 'Image';
        $data['url'] = $imageUrl;
        $data['expiredInHours'] = '24';
        $api->request($url, $data);
    }
}

createItem($api, $product);
updateItem($api, $product);
createExtendedProperties($api, $product);