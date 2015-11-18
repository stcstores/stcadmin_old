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

/*function getShippingMethods($api){
    $response = $api->getShippingMethods();
    $shippingMethods = array();
    foreach ($response as $method) {
        $shippingMethods[] = $method['name'];
    }
    return $shippingMethods;
}*/

function isValidPrice($price)
{
    if (is_numeric($price)) {
        return true;
    }
    return false;
}

/*function getNumberOfVariationsInPost() {
    $variationNumber = 0;
    foreach ($_POST as $detail=>$value) {
        if (substr($detail, 0, 8) == 'var_name') {
            $variationNumber++;
        }
    }
    return $variationNumber;
}*/





/*function list_pending_products() {
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
}*/



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
