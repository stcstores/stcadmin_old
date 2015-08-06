<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    
    $product = $_SESSION['new_product'];
    $products = array($product);
    if ($product->details['var_type']->value == true) {
        foreach ($product->variations as $variation){
            $products[] = $variation;
        }
    }
    

    $skuList = array();
    foreach ($products as $item) {
        $productNumber = 0;
        $imageList = array();
        $imageNumber = 0;
        foreach ($item->images->images as $image) {
            $imageData = array();
            $imageData['thumbPath'] = $image->thumbPath;
            $imageData['guid'] = $image->guid;
            if ($imageNumber == 0){
                $imageData['primary'] = true;
            } else {
                $imageData['primary'] = false;
            }
            $imageList[] = $imageData;
            
            $imageNumber++;
        }
        $sku = $item->details['sku']->text;
        if (array_key_exists('item_title', $item->details)) {
            $skuList[$sku]['title'] = htmlspecialchars($item->details['item_title']->text);
        } else {
            $skuList[$sku]['title'] = htmlspecialchars($item->details['var_name']->text);
        }
        $skuList[$sku]['images'] = $imageList;
        $productNumber ++;
        
    }

    echo json_encode($skuList);

?>