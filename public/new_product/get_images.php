<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    
    function get_image_details($product) {
        $data = array();
        $data['image_list'] = array();
        $imageNumber = 0;
        foreach ($product->images->images as $image) {
            $imageData = array();
            $imageData['thumbPath'] = $image->thumbPath;
            $imageData['guid'] = $image->guid;
            if ($imageNumber == 0){
                $imageData['primary'] = true;
            } else {
                $imageData['primary'] = false;
            }
            $data['image_data'][$imageNumber] = $imageData;
            $imageNumber++;
        }
        $data['sku'] = $product->details['sku']->text;
        if (array_key_exists('item_title', $product->details)) {
            $data['title'] = htmlspecialchars($product->details['item_title']->text);
        } else {
            $data['title'] = htmlspecialchars($product->details['var_name']->text);
        }
        
        return $data;
    }
    
    $product = $_SESSION['new_product'];
    
    
    
    $data = array();
    
    $data['product'] = get_image_details($product);
    $key_fields = $product->keyFields;
    if ($product->details['var_type']->value == true) {
        $data['variations'] = array();
        foreach ($product->variations as $variation){
            $variationData = get_image_details($variation);
            foreach ($key_fields as $field=>$value) {
                if ($value == true) {
                    $variationData['variations'][$field] = $variation->details[$field]->text;
                }
            }
            
            $data['variations'][] = $variationData;
        }
    }

    echo json_encode($data);

?>