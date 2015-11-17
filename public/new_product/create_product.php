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

function getCreateInventoryItem($product)
{
    $item = array();
    $item['ItemNumber'] = (string)$product->details['sku']->api_value;
    $item['ItemTitle'] = get_linn_title($product);
    $item['BarcodeNumber'] = (string)$product->details['barcode']->api_value;
    $item['PurchasePrice'] = (string)$product->details['purchase_price']->api_value;
    $item['RetailPrice'] = (string)$product->details['retail_price']->api_value;
    $item['Quantity'] = (string)$product->details['quantity']->api_value;
    $item['TaxRate'] = '0';
    $item['StockItemId'] = (string)$product->details['guid']->api_value;
    return $item;
}

function getCategoryId($api, $categoryName)
{
    $categoryInfo = $api->get_category_info();
    foreach ($categoryInfo as $category) {
        if ($category['name'] == $categoryName) {
            return $category['id'];
        }
    }
    echo "CATEGORY ID ERROR";
    exit;
}

function getPackageId($api, $groupName)
{
    $packageInfo = $api->get_packaging_group_info();
    foreach ($packageInfo as $group) {
        if ($group['name'] == $groupName) {
            return $group['id'];
        }
    }
    echo "PACKAGE ID ERROR";
    exit;
}

function getServiceId($api)
{
    $serviceInfo = $api->get_shipping_method_info();
    return $serviceInfo[0]['id'];
}

function getUpdateInventoryItem($api, $product)
{
    $item = array();
    $item['ItemNumber'] = (string)$product->details['sku']->api_value;
    $item['ItemTitle'] = get_linn_title($product);
    $item['BarcodeNumber'] = (string)$product->details['barcode']->api_value;
    $purchasePrice = (string)$product->details['purchase_price']->api_value;
    if ($purchasePrice == "") {
        $purchasePrice = "0";
    }
    $item['PurchasePrice'] = $purchasePrice;
    $retailPrice = (string)$product->details['retail_price']->api_value;
    if ($retailPrice == "") {
        $retailPrice = "0";
    }
    $item['RetailPrice'] = $retailPrice;
    $item['Quantity'] = (string)$product->details['quantity']->api_value;
    $item['TaxRate'] = '0';
    $item['StockItemId'] = (string)$product->details['guid']->api_value;
    $item['VariationGroupName'] = '';
    $item['MetaData'] = (string)$product->details['short_description']->api_value;
    $item['CategoryId'] = getCategoryId($api, $product->details['department']->api_value);
    $item['PackageGroupId'] = getPackageId($api, $product->details['shipping_method']->api_value);
    $item['PostalServiceId'] = getServiceId($api);
    $item['Weight'] = (string)$product->details['weight']->api_value;
    $item['Width'] = (string)$product->details['width']->api_value;
    $item['Depth'] = (string)$product->details['depth']->api_value;
    $item['Height'] = (string)$product->details['height']->api_value;
    return $item;
}

function createItem($api, $product)
{
    $url = $api -> server . '/api/Inventory/AddInventoryItem';
    $dataArray = getCreateInventoryItem($product);
    $dataJson = json_encode($dataArray);
    $data = array('inventoryItem' => $dataJson);
    echo "<br />\n";
    echo "Create Item Request " . $product->details['sku']->text . "\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api -> request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function updateItem($api, $product)
{
    $url = $api -> server . '/api/Inventory/UpdateInventoryItem';
    $dataArray = getUpdateInventoryItem($api, $product);
    $dataJson = json_encode($dataArray);
    $data = array('inventoryItem' => $dataJson);
    echo "<br />\n";
    echo "Update Item Request " . $product->details['sku']->text . "\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api -> request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function createExtendedProperties($api, $product)
{
    if ($product->details['var_type']->value == false) {
        requestExtendedProperties($api, $product, 'single');
    } else {
        requestExtendedProperties($api, $product, 'group');
        foreach ($product->variations as $variation) {
            requestExtendedProperties($api, $variation, 'variation');
        }
    }
}

function requestExtendedProperties($api, $product, $product_type)
{
    $url = $api -> server . '/api/Inventory/CreateInventoryItemExtendedProperties';
    $dataArray = getExtendedPropertiesArray($product, $product_type);
    print_r($dataArray);
    echo "<br />";
    $dataJson = json_encode($dataArray);
    //str_replace(array('\n'), '', $dataJson);
    echo "<br />";
    echo $dataJson;
    echo "<br />";
    $data = array('inventoryItemExtendedProperties' => $dataJson);
    echo "<br />\n";
    echo "Extended Properties Request " . $product->details['sku']->text . "\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api -> request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function createExtendedProperty($product, $name, $value, $type)
{
    $exProp = array();
    $exProp['pkRowId'] = createGUID();
    $exProp['fkStockItemId'] = (string)$product->details['guid']->api_value;
    $exProp['ProperyName'] = $name;
    $exProp['PropertyValue'] = $value;
    $exProp['PropertyType'] = $type;
    return $exProp;
}

function getExtendedPropertiesArray($product, $product_type)
{
    $extendedProperties = array();
    $properties = array(
        array('Manufacturer', (string)$product->details['manufacturer']->text, 'Attribute'),
        array('Brand', (string)$product->details['brand']->text, 'Attribute'),
        array('Location', (string)$product->details['location']->text, 'Attribute'),
        array('MPN', (string)$product->details['mpn']->text, 'Attribute'),
        array('Size', (string)$product->details['size']->text, 'Attribute'),
        array('Colour', (string)$product->details['colour']->text, 'Attribute'),
        array('Material', (string)$product->details['material']->text, 'Attribute'),
        array('Age', (string)$product->details['age']->text, 'Attribute'),
        array('Design', (string)$product->details['design']->text, 'Attribute'),
        array('Shape', (string)$product->details['shape']->text, 'Attribute'),
        array('Texture', (string)$product->details['texture']->text, 'Attribute'),
        array('Style', (string)$product->details['style']->text, 'Attribute'),
        array('VAT Free', (string)$product->details['vat_free']->text, 'Attribute'),
        array('Shipping FR', (string)$product->details['shipping_fr']->text, 'Attribute'),
        array('Shipping DE', (string)$product->details['shipping_de']->text, 'Attribute'),
        array('Shipping EU', (string)$product->details['shipping_eu']->text, 'Attribute'),
        array('Shipping USA', (string)$product->details['shipping_usa']->text, 'Attribute'),
        array('Shipping AUS', (string)$product->details['shipping_aus']->text, 'Attribute'),
        array('Shipping ROW', (string)$product->details['shipping_row']->text, 'Attribute'),
        array('stcadmin', 'TRUE', 'Attribute')
    );

    if ($product_type == 'variation') {
        foreach ($product->product->keyFields as $varType => $varValue) {
            if ($varValue == true) {
                $properties[] = array('var_' . $varType, (string)$product->details[$varType]->text, 'Attribute');
            }
        }
    }

    foreach ($properties as $prop) {
        $exProp = createExtendedProperty($product, $prop[0], $prop[1], $prop[2]);
        $extendedProperties[] = $exProp;
    }
    return $extendedProperties;
}

function getImageAssignArrays($products)
{
    $imageArrays = array();
    foreach ($products as $item) {
        $guid = $item->details['guid']->api_value;
        $imageArrays[$guid] = array();
        foreach ($item->images->images as $image) {
            $imageArrays[$guid][] = $image->guid;
        }
    }

    return $imageArrays;

}

function getPrimaryImages($products)
{
    $primaryImages = array();
    foreach ($products as $item) {
        if (count($item->images) > 0) {
            $guid = $item->details['guid']->api_value;
            $imageGuid = $item->images->images[0]->guid;
            $primaryImages[$guid] = $imageGuid;
        }
    }
    return $primaryImages;
}

function assign_images($api, $product)
{
    $product = $_SESSION['new_product'];
    $products = array($product);
    if ($product->details['var_type']->value == true) {
        foreach ($product->variations as $variation) {
            $products[] = $variation;
        }
    }
    foreach (getImageAssignArrays($products) as $productGuid => $imageArray) {
        echo "<br />\n";
        echo "Assign Image Request " . $productGuid . "\n";
        echo "<br />\n";
        echo "<p>Request</p>\n";
        print_r($imageArray);
        echo "<br />\n";
        echo "<p>Response</p>\n";
        print_r($api->assign_images($productGuid, $imageArray));
        echo "<br />\n";
        echo "<hr>\n";
    }
    foreach (getPrimaryImages($products) as $guid => $imageGuid) {
        echo "<br />\n";
        echo "Assign Image Request " . $guid . "\n";
        echo "<br />\n";
        echo "<p>Request</p>\n";
        print_r($imageGuid);
        echo "<br />\n";
        echo "<p>Response</p>\n";
        print_r($api->set_primary_image($guid, $imageGuid));
        echo "<br />\n";
        echo "<hr>\n";
    }
}

function getCreateVariationGroupTemplate($product)
{
    $template = array();
    $template['ParentSKU'] = $product->details['sku']->api_value;
    $template['VariationGroupName'] = $product->details['item_title']->api_value;
    $template['ParentStockItemId'] = $product->details['guid']->api_value;
    $variationIds = array();
    foreach ($product->variations as $variation) {
        $variationIds[] = $variation->details['guid']->api_value;
    }
    $template['VariationItemIds'] = $variationIds;
    return json_encode($template);
}

function createVariationGroup($api, $product)
{
    $url = $api->server . '/api/Stock/CreateVariationGroup';
    $data = array();
    $data['template'] = getCreateVariationGroupTemplate($product);
    echo "Assign Image Request " . $product->details['sku']->text . "\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api->request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function addTitles($api, $product)
{
    $url = $api->server . '/api/Inventory/CreateInventoryItemTitles';
    $inventoryItemTitles = array();
    foreach (getAddTitlesForProduct($product) as $channel) {
        $inventoryItemTitles[] = $channel;
    }
    $data = array();
    $data['inventoryItemTitles'] = json_encode($inventoryItemTitles);
    echo "Create Item Titles Request\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api->request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function getAddTitlesForProduct($product)
{
    $guid = $product->details['guid']->text;
    $ebay = array();
    $ebay['pkRowId'] = createGUID();
    $ebay['Source'] = 'EBAY';
    $ebay['SubSource'] = 'EBAY0';
    $ebay['Title'] = $product->details['ebay_title']->api_value;
    $ebay['StockItemId'] = $guid;

    $amazon = array();
    $amazon['pkRowId'] = createGUID();
    $amazon['Source'] = 'AMAZON';
    $amazon['SubSource'] = 'Stc Stores';
    $amazon['Title'] = $product->details['item_title']->api_value;
    $amazon['StockItemId'] = $guid;

    $shopify = array();
    $shopify['pkRowId'] = createGUID();
    $shopify['Source'] = 'SHOPIFY';
    $shopify['SubSource'] = 'stcstores.co.uk (shopify)';
    $shopify['Title'] = $product->details['item_title']->api_value;
    $shopify['StockItemId'] = $guid;

    return array($ebay, $amazon, $shopify);
}

function addPrices($api, $product)
{
    $url = $api->server . '/api/Inventory/CreateInventoryItemPrices';
    $inventoryItemPrices = array();
    foreach (getAddPricesForProduct($product) as $channel) {
        $inventoryItemPrices[] = $channel;
    }
    $data = array();
    $data['inventoryItemPrices'] = json_encode($inventoryItemPrices);
    echo "Create Item Prices Request\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api->request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function getAddPricesForProduct($product)
{
    $price = $product->details['retail_price']->value;
    $priceWithShipping = $price +  $product->details['shipping_price']->value;

    $guid = $product->details['guid']->api_value;
    $ebay = array();
    $ebay['pkRowId'] = createGUID();
    $ebay['Source'] = 'EBAY';
    $ebay['SubSource'] = 'EBAY0';
    $ebay['Price'] = (string)$priceWithShipping;
    $ebay['StockItemId'] = $guid;

    $amazon = array();
    $amazon['pkRowId'] = createGUID();
    $amazon['Source'] = 'AMAZON';
    $amazon['SubSource'] = 'Stc Stores';
    $amazon['Price'] = (string)$priceWithShipping;
    $amazon['StockItemId'] = $guid;

    $shopify = array();
    $shopify['pkRowId'] = createGUID();
    $shopify['Source'] = 'SHOPIFY';
    $shopify['SubSource'] = 'stcstores.co.uk (shopify)';
    $shopify['Price'] = $price;
    $shopify['StockItemId'] = $guid;

    return array($ebay, $amazon, $shopify);
}

function addDescriptions($api, $product)
{
    $url = $api->server . '/api/Inventory/CreateInventoryItemDescriptions';
    $inventoryItemDescriptions = array();
    foreach (getAddDescriptionsForProduct($product) as $channel) {
        $inventoryItemDescriptions[] = $channel;
    }
    $data = array();
    $data['inventoryItemDescriptions'] = json_encode($inventoryItemDescriptions);
    echo "Create Item Descriptions Request\n";
    echo "<br />\n";
    echo "<p>Request</p>\n";
    print_r($data);
    echo "<br />\n";
    echo "<p>Response</p>\n";
    print_r($api->request($url, $data));
    echo "<br />\n";
    echo "<hr>\n";
}

function getAddDescriptionsForProduct($product)
{
    $guid = $product->details['guid']->api_value;
    $ebay = array();
    $ebay['pkRowId'] = createGUID();
    $ebay['Source'] = 'EBAY';
    $ebay['SubSource'] = 'EBAY0';
    $ebay['Description'] = to_html($product->details['short_description']->api_value);
    $ebay['StockItemId'] = $guid;

    $amazon = array();
    $amazon['pkRowId'] = createGUID();
    $amazon['Source'] = 'AMAZON';
    $amazon['SubSource'] = 'Stc Stores';
    $amazon['Description'] = $product->details['short_description']->api_value;
    $amazon['StockItemId'] = $guid;

    $shopify = array();
    $shopify['pkRowId'] = createGUID();
    $shopify['Source'] = 'SHOPIFY';
    $shopify['SubSource'] = 'stcstores.co.uk (shopify)';
    $shopify['Description'] = to_html($product->details['short_description']->api_value);
    $shopify['StockItemId'] = $guid;

    return array($ebay, $amazon, $shopify);
}

if ($product->details['var_type']->value == true) {
    foreach ($product->variations as $variation) {
        createItem($api, $variation);
        updateItem($api, $variation);
        addPrices($api, $variation);
    }
    createVariationGroup($api, $product);
    $new_guid = $api->getVariationGroupIdBySKU($product->details['sku']->api_value);
    $product->details['guid']->set($new_guid);
} else {
    createItem($api, $product);
    addPrices($api, $product);
}
updateItem($api, $product);
createExtendedProperties($api, $product);
assign_images($api, $product);
addTitles($api, $product);
addDescriptions($api, $product);
