<?php

set_time_limit ( 150 );

function getValuesFromDatabase($table, $column){
    $database = new DatabaseConnection();
    $query = "SELECT {$column} FROM {$table} ORDER BY is_default DESC, {$column};";
    //echo $query;
    $results = $database -> selectQuery($query);
    foreach ($results as $result) {
        $resultArray[] = $result[$column];
    }
    return $resultArray;
}

function getDatabaseColumn($table, $column){
    $database = new DatabaseConnection();
    $query = "SELECT {$column} FROM {$table};";
    //echo $query;
    $results = $database -> selectQuery($query);
    foreach ($results as $result) {
        $resultArray[] = $result[$column];
    }
    return $resultArray;
}

function getShippingMethods($api){
    $response = $api->getShippingMethods();
    $shippingMethods = array();
    foreach ($response as $method) {
        $shippingMethods[] = $method['name'];
    }
    return $shippingMethods;
}

function getFormFieldsByPage($page){
    $database = new DatabaseConnection();
    $query = "SELECT * FROM new_product_form_field WHERE page='{$page}' ORDER BY position;";
    $results = $database->selectQuery($query);
    return $results;
}

function generateSku() {
    $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
    return $api->get_new_sku();
}

function addSkuToDatabase($sku) {
    $database = new DatabaseConnection();
    $insertQuery = "INSERT INTO skus (sku) VALUES ('{$sku}');";
    $database->insertQuery($insertQuery);
}

function getExistingSkus() {
    $database = new DatabaseConnection();
    $existingSKUs = $database->getColumn('skus', 'sku');
    return $existingSKUs;
}

function isValidPrice($price) {
    if (is_numeric($price)) {
        return true;
    }
    return false;

}

function getNumberOfVariationsInPost() {
    $variationNumber = 0;
    foreach ($_POST as $detail=>$value) {
        if (substr($detail, 0, 8) == 'var_name') {
            $variationNumber++;
        }
    }
    return $variationNumber;
}

function getVarSetupFields() {
    $varSetup = getFormFieldsByPage('var_setup');
    $extendedProperties = getFormFieldsByPage('extended_properties');
    foreach ($varSetup as $varSetupField) {
        $fields[] = $varSetupField;
    }
    foreach ($extendedProperties as $property) {
        $fields[] = $property;
    }

    return $fields;
}

function getVarSetupValues() {
    if (isset($_SESSION['new_product'])) {
        $product = $_SESSION['new_product'];
        $variations = array();
        foreach ($product->variations as $variation) {
            foreach ($variation->details as $key => $value) {
                $newArray[$key] = htmlspecialchars($value->text);
            }
            $variations[] = $newArray;
        }
        return $variations;
    } else {
        return null;
    }
}

function hasImageExtenstion($filename){
    $imageExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), $imageExtensions)) {
        return true;
    } else {
        return false;
    }
}

function imageToDatabase($image, $sku, $primary, $extension) {
    $imageDatabase = new DatabaseConnection();
    $imageData = imageToBinary($image);
    $insertQuery = "INSERT INTO images (image, is_primary, extension, sku) VALUES ('" . $imageData . "', '" . $primary . "','" . strtolower($extension) . "', '" . $sku . "');";
    $imageDatabase->insertQuery($insertQuery);
}

function skuHasImages($sku) {
    $imageDatabase = new DatabaseConnection();
    $selectQuery = "SELECT id FROM images WHERE sku='{$sku}';";
    $imageResults = $imageDatabase->selectQuery($selectQuery);
    if (count($imageResults) > 0) {
        return true;
    } else {
        return false;
    }
}

function setImagePrimary($sku, $imageId) {
    $removePrimeQuery = "UPDATE images SET is_primary='0' WHERE sku='{$sku}';";
    $setPrimeQuery = "UPDATE images SET is_primary='1' WHERE sku='{$sku}' AND id={$imageId}; ";
    $imageDatabase = new DatabaseConnection();
    $imageDatabase->insertQuery($removePrimeQuery);
    $imageDatabase->insertQuery($setPrimeQuery);
}

function getImageIdsForSKU($sku) {
    $imageIds = array();
    $selectQuery = "SELECT id, is_primary FROM images WHERE sku='{$sku}' ORDER BY is_primary;";
    $imageDatabase = new DatabaseConnection();
    $imageResults = $imageDatabase->selectQuery($selectQuery);
    $idArray = array();
    foreach ($imageResults as $imageResult) {
        $idArray[] = array('id' =>$imageResult['id'], 'is_primary' =>$imageResult['is_primary']);
    }
    return $idArray;
}

function getExtendedProperties() {
    $database = new DatabaseConnection();
    $selectQuery = "SELECT field_name, field_title FROM new_product_form_field WHERE csv='extended'";
    $results = $database->selectQuery($selectQuery);
    $extendedProps = array();
    foreach ($results as $result) {
        $extendedProps[] = array('field_name' => $result['field_name'], 'field_title' => $result['field_title']);
    }
    return $extendedProps;
}

function getSpecialCharacters() {
    $selectQuery = "SELECT sc, name FROM special_characters;";
    $database = new DatabaseConnection();
    $results = $database->selectQuery($selectQuery);
    return $results;
}

function getKeyFields() {
    $selectQuery = "SELECT field_name, field_title FROM new_product_form_field WHERE `can_be_key` = TRUE ORDER BY position;";
    $database = new DatabaseConnection();
    $results = $database->selectQuery($selectQuery);
    return $results;
}

function list_pending_products() {
    $basicInfoCsv = new BasicInfoFile();
    $basicInfo = $basicInfoCsv->read();
    $newVarCsv = new NewVarGroupFile();
    $newVar = $newVarCsv->read();
    $addVarCsv = new AddToVarGroupFile();
    $addVar = $addVarCsv->read();
    $pending_products = array();

    $i = 0;
    foreach ($newVar['VariationSKU'] as $row) {
        $pending_product = array();
        $pending_product['SKU'] = $newVar['VariationSKU'][$i];
        $pending_product['Title'] = $newVar['VariationGroupName'][$i];
        $pending_products[] = $pending_product;
        $i++;
    }

    $varSKUs = $addVar['SKU'];

    $i = 0;
    foreach($basicInfo['SKU'] as $SKU) {
        if (!(in_array($SKU, $varSKUs))) {
            $pending_product = array();
            $pending_product['SKU'] = $basicInfo['SKU'][$i];
            $pending_product['Title'] = $basicInfo['Title'][$i];
            $pending_products[] = $pending_product;
        }
        $i++;
    }

    return $pending_products;
}

function createGUID() {
    $guid = shell_exec('python ' . dirname($_SERVER['DOCUMENT_ROOT']) . '/private/get_uuid.py');
    $guid = str_replace(array("\r", "\n"), '', $guid);
    return $guid;

}

function to_html($string) {
    $lines = explode("\n", $string);
    $new_string = "";
    foreach ($lines as $line) {
        if (trim($line) == '') {
            $new_string = $new_string . "<br />\n";
        } else {
            $new_string = $new_string . "<p>" . trim($line) . "</p>\n";
        }
    }
    return $new_string;
}

function get_international_shipping($weight) {
    $csv = new InternationalShippingLookup();

    $table = $csv->read();

    $i = 1;

    if ($weight > $table['weight'][1]) {
        while ($table['weight'][$i] < $weight) {
            $i++;
        }

        $i --;
    }

    $weights = array();
    $weights['fr'] = $table['fr'][$i];
    $weights['de'] = $table['de'][$i];
    $weights['eu'] = $table['eu'][$i];
    $weights['usa'] = $table['usa'][$i];
    $weights['aus'] = $table['aus'][$i];
    $weights['row'] = $table['row'][$i];

    return $weights;
}

function get_linn_title_single_item($product)
{
    $item_title = $product->details['item_title']->text;
    $location = $product->details['location']->text;
    $mpn = $product->details['mpn']->text;
    if (strlen($mpn) > 0) {
        $item_title = $mpn . ' ' . $item_title;
    }

    if (strlen($location) > 0) {
        $item_title = $location . ' ' . $item_title;
    }
    return $item_title;
}

function get_linn_title_var_parent($product)
{
    $item_title = $product->details['item_title']->text;
    $mpn_match = true;
    $mpn = $product->variations[0]->details['mpn']->text;
    foreach ($product->variations as $variation) {
        $this_mpn = $variation->details['mpn']->text;
        if ($this_mpn == '' || $this_mpn != $mpn) {
            $mpn_match = false;
        }
    }
    if ($mpn_match) {
        $item_title = $mpn . ' ' . $item_title;
    }
    return $item_title;
}

function get_linn_title_variation($variation)
{
    $product_title = $variation->product->details['item_title']->text;
    $location = $variation->details['location']->text;
    $mpn = $variation->details['mpn']->text;
    $var_append = $variation->details['var_append']->text;
    $item_title = '';
    if (strlen($location) > 0) {
        $item_title = $item_title . $location . ' ';
    }
    if (strlen($mpn) > 0) {
        $item_title = $item_title . $mpn . ' ';
    }
    $item_title = $item_title . $product_title . ' ';
    $keyFields = $variation->product->keyFields;
    foreach ($keyFields as $field => $isKey) {
        if ($isKey) {
            $item_title = $item_title . '{ ';
            $item_title = $item_title . $variation->details[$field]->text;
            $item_title = $item_title . ' } ';
        }
    }
    if ($var_append != '') {
        $item_title += $var_append;
    }
    return trim($item_title);
}

function get_linn_title($product)
{
    if ($product instanceof NewVariation) {
        $item_title = get_linn_title_variation($product);
    } else {
        if (count($product->variations) > 0) {
            $item_title = get_linn_title_var_parent($product);
        } else {
            $item_title = get_linn_title_single_item($product);
        }
    }
    return $item_title;
}
