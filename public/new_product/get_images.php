<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    
    $product = $_SESSION['new_product'];
    
    if ($product->details['var_type']->value == true) {
        $products = $product->variations;
    } else {
        $products = array($product);
    }
    

    $skuList = array();
    foreach ($products as $item) {        
        $imageList = array();
        $i = 0;
        foreach ($item->images->images as $image) {
            $imageData = array();
            $imageData['thumbPath'] = $image->thumbPath;
            $imageData['guid'] = $image->guid;
            if ($i == 0){
                $imageData['primary'] = true;
            } else {
                $imageData['primary'] = false;
            }
            $imageList[] = $imageData;
            
            $i++;
        }
        $sku = $product->details['sku']->text;
        $skuList[$sku]['title'] = htmlspecialchars($item->details['item_title']->text);
        $skuList[$sku]['images'] = $imageList;
    }

    echo json_encode($skuList);

?>