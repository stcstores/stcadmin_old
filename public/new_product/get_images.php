<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    
    $product = $_SESSION['new_product'];
    
    if ($product->details['var_type']->value == true) {
        $skus = array();
        foreach ($product->variations as $variation) {
            $skus[$variation->details['sku']->text] = $variation->details['var_name']->text;
        }
    } else {
        $skus = array($product->details['sku']->text => $product->details['item_title']->text);
    }
    

    $imageDatabase = new DatabaseConnection();
    $skuList = array();
    foreach ($skus as $sku => $title) {        
        $selectQuery = ("SELECT * FROM images WHERE sku='{$sku}' ORDER BY is_primary DESC;");
        $imageResults = $imageDatabase->selectQuery($selectQuery);
        //print_r($imageResults);
        $imageList = array();
        foreach ($imageResults as $image) {
            $imageData = array();
            $imageData['id'] = $image['id'];
            $imageData['primary'] = $image['is_primary'];
            $imageList[] = $imageData;
        }
        $skuList[$sku]['title'] = htmlspecialchars($title);
        $skuList[$sku]['images'] = $imageList;
    }

    echo json_encode($skuList);

?>