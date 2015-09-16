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
    if (array_key_exists('var_name', $product->details)){
        $item['ItemTitle'] = (string)$product->details['var_name']->text;
    } else {
        $item['ItemTitle'] = (string)$product->details['item_title']->text;
    }
    $item['BarcodeNumber'] = (string)$product->details['barcode']->text;
    $item['PurchasePrice'] = (string)$product->details['purchase_price']->text;
    $item['RetailPrice'] = (string)$product->details['retail_price']->text;
    $item['Quantity'] = (string)$product->details['quantity']->text;
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
    echo "CATEGORY ID ERROR";
    exit;
}

function getPackageId($api, $groupName) {
    $packageInfo = $api->getPackagingGroupInfo();
    foreach ($packageInfo as $group) {
        if ($group['name'] == $groupName) {
            return $group['id'];
        }
    }
    echo "PACKAGE ID ERROR";
    exit;
}

function getServiceId($api) {
    $serviceInfo = $api->getShippingMethodInfo();
    return $serviceInfo[0]['id'];
}

function getUpdateInventoryItem($api, $product) {
    $item = array();
    $item['ItemNumber'] = (string)$product->details['sku']->text;
    if (array_key_exists('item_title', $product->details)) {
        $item['ItemTitle'] = (string)$product->details['item_title']->text;
    } else {
        $item['ItemTitle'] = (string)$product->details['var_name']->text;
    }
    $item['BarcodeNumber'] = (string)$product->details['barcode']->text;
    $purchasePrice = (string)$product->details['purchase_price']->text;
    if ($purchasePrice == "") {
        $purchasePrice = "0";
    }
    $item['PurchasePrice'] = $purchasePrice;
    $retailPrice = (string)$product->details['retail_price']->text;
    if ($retailPrice == "") {
        $retailPrice = "0";
    }
    $item['RetailPrice'] = $retailPrice;
    $item['Quantity'] = (string)$product->details['quantity']->text;
    $item['TaxRate'] = '0';
    $item['StockItemId'] = (string)$product->details['guid']->text;
    $item['VariationGroupName'] = '';
    $item['MetaData'] = (string)$product->details['short_description']->text;
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

function updateItem($api, $product) {
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

function createExtendedProperties($api, $product) {
    if ($product->details['var_type']->value == false) {
        requestExtendedProperties($api, $product, 'single');
    } else {
        requestExtendedProperties($api, $product, 'group');
        foreach ($product->variations as $variation) {
            requestExtendedProperties($api, $variation, 'variation');
        }
    }
}

function requestExtendedProperties($api, $product, $type) {
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

function createExtendedProperty($product, $name, $value, $type) {
    $exProp = array();
    $exProp['pkRowId'] = createGUID();
    $exProp['fkStockItemId'] = (string)$product->details['guid']->text;
    $exProp['ProperyName'] = $name;
    $exProp['PropertyValue'] = $value;
    $exProp['PropertyType'] = $type;
    return $exProp;
}

function getExtendedPropertiesArray($product, $type) {
    $extendedProperties = array();
    $properties = array(
        array('Manufacturer', (string)$product->details['manufacturer']->text, 'Attribute'),
        array('Brand', (string)$product->details['brand']->text, 'Attribute'),
        array('Size', (string)$product->details['size']->text, 'Attribute'),
        array('Colour', (string)$product->details['colour']->text, 'Attribute'),
        array('Material', (string)$product->details['material']->text, 'Attribute'),
        array('Style', (string)$product->details['style']->text, 'Attribute'),
        array('Amazon_Bullet_1', (string)$product->details['am_bullet_1']->text, 'Attribute'),
        array('Amazon_Bullet_2', (string)$product->details['am_bullet_2']->text, 'Attribute'),
        array('Amazon_Bullet_3', (string)$product->details['am_bullet_3']->text, 'Attribute'),
        array('Amazon_Bullet_4', (string)$product->details['am_bullet_4']->text, 'Attribute'),
        array('Amazon_Bullet_5', (string)$product->details['am_bullet_5']->text, 'Attribute'),
        array('VAT Free', (string)$product->details['vat_free']->text, 'Attribute'),
        array('InternationalShipping', (string)$product->details['int_shipping']->text, 'Attribute')
    );
    
    if ($type == 'variation') {
        foreach ($product->keyFields as $varType => $varValue) {
            if ($varValue == true) {
                $properties[] = array('var_' . $varType), (string)$product->details[$varType]->text, 'Attribute');
            }
        }
    }
    
    foreach ($properties as $prop) {
        $exProp = createExtendedProperty($product, $prop[0], $prop[1], $prop[2]);
        $extendedProperties[] = $exProp;
    }
    return $extendedProperties;
}

function getImageAssignArrays($products) {
    $imageArrays = array();
    foreach ($products as $item) {
        $guid = $item->details['guid']->text;
        $imageArrays[$guid] = array();
        foreach ($item->images->images as $image) {
            $imageArrays[$guid][] = $image->guid;
        }
    }
    
    return $imageArrays;
    
}

function getPrimaryImages($products) {
    $primaryImages = array();
    foreach ($products as $item) {
        $guid = $item->details['guid']->text;
        $imageGuid = $item->images->images[0]->guid;
        $primaryImages[$guid] = $imageGuid;
    }
    return $primaryImages;
}

function assignImages($api, $product) {
    $product = $_SESSION['new_product'];
    $products = array($product);
    if ($product->details['var_type']->value == true) {
        foreach ($product->variations as $variation){
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
        print_r($api->assignImages($productGuid, $imageArray));
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
        print_r($api->setPrimaryImage($guid, $imageGuid));
        echo "<br />\n";
        echo "<hr>\n";
    }
}

function getCreateVariationGroupTemplate($product) {
    $template = array();
    $template['ParentSKU'] = $product->details['sku']->text;
    $template['VariationGroupName'] = $product->details['item_title']->text;
    $template['ParentStockItemId'] = $product->details['guid']->text;
    $variationIds = array();
    foreach ($product->variations as $variation) {
        $variationIds[] = $variation->details['guid']->text;
    }
    $template['VariationItemIds'] = $variationIds;
    return json_encode($template);
}

function createVariationGroup($api, $product) {
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

function addTitles($api, $product) {
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

function getAddTitlesForProduct($product) {
    $guid = $product->details['guid']->text;
    $ebay = array();
    $ebay['pkRowId'] = createGUID();
    $ebay['Source'] = 'EBAY';
    $ebay['SubSource'] = 'EBAY0';
    $ebay['Title'] = $product->details['ebay_title']->text;
    $ebay['StockItemId'] = $guid;
    
    $amazon = array();
    $amazon['pkRowId'] = createGUID();
    $amazon['Source'] = 'AMAZON';
    $amazon['SubSource'] = 'Stc Stores';
    $amazon['Title'] = $product->details['am_title']->text;
    $amazon['StockItemId'] = $guid;
    
    $shopify = array();
    $shopify['pkRowId'] = createGUID();
    $shopify['Source'] = 'SHOPIFY';
    $shopify['SubSource'] = 'stcstores.co.uk (shopify)';
    $shopify['Title'] = $product->details['shopify_title']->text;
    $shopify['StockItemId'] = $guid;
    
    return array($ebay, $amazon, $shopify);
}

function addDescriptions($api, $product) {
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

function getAddDescriptionsForProduct($product) {
    $guid = $product->details['guid']->text;
    $ebay = array();
    $ebay['pkRowId'] = createGUID();
    $ebay['Source'] = 'EBAY';
    $ebay['SubSource'] = 'EBAY0';
    $ebay['Description'] = $product->details['ebay_description']->text;
    $ebay['StockItemId'] = $guid;
    
    $amazon = array();
    $amazon['pkRowId'] = createGUID();
    $amazon['Source'] = 'AMAZON';
    $amazon['SubSource'] = 'Stc Stores';
    $amazon['Description'] = $product->details['am_description']->text;
    $amazon['StockItemId'] = $guid;
    
    $shopify = array();
    $shopify['pkRowId'] = createGUID();
    $shopify['Source'] = 'SHOPIFY';
    $shopify['SubSource'] = 'stcstores.co.uk (shopify)';
    $shopify['Description'] = $product->details['shopify_description']->text;
    $shopify['StockItemId'] = $guid;
    
    return array($ebay, $amazon, $shopify);
}

function addPrices($api, $product) {
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

function getAddPricesForProduct($product) {
    $guid = $product->details['guid']->text;
    $ebay = array();
    $ebay['pkRowId'] = createGUID();
    $ebay['Source'] = 'EBAY';
    $ebay['SubSource'] = 'EBAY0';
    $ebay['Price'] = $product->details['retail_price']->text;
    $ebay['StockItemId'] = $guid;
    
    $amazon = array();
    $amazon['pkRowId'] = createGUID();
    $amazon['Source'] = 'AMAZON';
    $amazon['SubSource'] = 'Stc Stores';
    $amazon['Price'] = $product->details['retail_price']->text;
    $amazon['StockItemId'] = $guid;
    
    $shopify = array();
    $shopify['pkRowId'] = createGUID();
    $shopify['Source'] = 'SHOPIFY';
    $shopify['SubSource'] = 'stcstores.co.uk (shopify)';
    $shopify['Price'] = $product->details['retail_price']->text;
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
    $new_guid = $api->getVariationGroupIdBySKU($product->details['sku']->text);
    $product->details['guid']->set($new_guid);
} else {
    createItem($api, $product);
    addPrices($api, $product);
}
updateItem($api, $product);
createExtendedProperties($api, $product);
assignImages($api, $product);
addTitles($api, $product);
addDescriptions($api, $product);
