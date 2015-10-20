<?php

function add_basic_info() {
    $product = $_SESSION['new_product'];
        
    if (isset($_POST['item_title'])) {
        $product->details['item_title']->set($_POST['item_title']);
    }
    
    if (isset($_POST['var_type'])) {
        $product->details['var_type']->set(true);
    } else {
        $product->details['var_type']->set(false);
        $product->variations = [];
    }
    
    if (isset($_POST['department'])) {
        $product->details['department']->set($_POST['department']);
    }
    
    if (isset($_POST['brand'])) {
        $product->details['brand']->set($_POST['brand']);
    }
    
    if (isset($_POST['manufacturer'])) {
        $product->details['manufacturer']->set($_POST['manufacturer']);
    }
    
    if (isset($_POST['shipping_method'])) {
        $product->details['shipping_method']->set($_POST['shipping_method']);
    }
    
    if (isset($_POST['short_description'])) {
        $product->details['short_description']->set($_POST['short_description']);
    }
    
    if ($product->details['shipping_method']->text == 'Packet') {
        $product->details['shipping_price']->set(2.95);
    } else if ($product->details['shipping_method']->text == 'Courier') {
        $product->details['shipping_price']->set(6.70);
    } else if ($product->details['shipping_method']->text == 'Large Letter') {
        $product->details['shipping_price']->set(1.00);
    }
    
    return $product;
}

function add_extended_properties($product) {
    if (isset($_POST['weight'])) {
        $product->details['weight']->set($_POST['weight']);
    }
    
    if (isset($_POST['location'])) {
        $product->details['location']->set($_POST['location']);
    }
    
    if (isset($_POST['mpn'])) {
        $product->details['mpn']->set($_POST['mpn']);
    }
    
    if (isset($_POST['int_shipping'])) {
        $product->details['int_shipping']->set('TRUE');
    } else {
        $product->details['int_shipping']->set('FALSE');
    }
    
    if (isset($_POST['vat_free'])) {
        $product->details['vat_free']->set('TRUE');
    } else {
        $product->details['vat_free']->set('FALSE');
    }
    
    if (isset($_POST['retail_price'])) {
        $product->details['retail_price']->set($_POST['retail_price']);
    }
    
    if (isset($_POST['purchase_price'])) {
        $product->details['purchase_price']->set($_POST['purchase_price']);
    }
    
    if (isset($_POST['shipping_price'])) {
        $product->details['shipping_price']->set($_POST['shipping_price']);
    }
    
    if (isset($_POST['barcode'])) {
        $product->details['barcode']->set($_POST['barcode']);
    }
        
    if (isset($_POST['height'])) {
        $product->details['height']->set($_POST['height']);
    }
    
    if (isset($_POST['width'])) {
        $product->details['width']->set($_POST['width']);
    }
    
    if (isset($_POST['depth'])) {
        $product->details['depth']->set($_POST['depth']);
    }
    
    if (isset($_POST['material'])) {
        $product->details['material']->set($_POST['material']);
    }
    
    if (isset($_POST['age'])) {
        $product->details['age']->set($_POST['age']);
    }
    
    if (isset($_POST['design'])) {
        $product->details['design']->set($_POST['design']);
    }
    
    if (isset($_POST['shape'])) {
        $product->details['shape']->set($_POST['shape']);
    }
    
    if (isset($_POST['texture'])) {
        $product->details['texture']->set($_POST['texture']);
    }
    
    if (isset($_POST['style'])) {
        $product->details['style']->set($_POST['style']);
    }
    
    if (isset($_POST['colour'])) {
        $product->details['colour']->set($_POST['colour']);
    }
    
    if (isset($_POST['size'])) {
        $product->details['size']->set($_POST['size']);
    }
    
    if (isset($_POST['quantity'])) {
        $product->details['quantity']->set($_POST['quantity']);
    }
    
    $intPostagePrices = get_international_shipping($product->details['weight']->value);
    $product->details['shipping_fr']->set($intPostagePrices['fr']);
    $product->details['shipping_de']->set($intPostagePrices['de']);
    $product->details['shipping_eu']->set($intPostagePrices['eu']);
    $product->details['shipping_usa']->set($intPostagePrices['usa']);
    $product->details['shipping_aus']->set($intPostagePrices['aus']);
    $product->details['shipping_row']->set($intPostagePrices['row']);
}

function add_chn_ebay($product) {
    if (isset($_POST['ebay_title'])) {
        $product->details['ebay_title']->set($_POST['ebay_title']);
    }
    
    if (isset($_POST['ebay_description'])) {
        $product->details['ebay_description']->set($_POST['ebay_description']);
    }
}

function add_chn_amazon($product) {
    if (isset($_POST['am_title'])) {
        $product->details['am_title']->set($_POST['am_title']);
    }
    
    if (isset($_POST['am_bullet_1'])) {
        $product->details['am_bullet_1']->set($_POST['am_bullet_1']);
    }
    
    if (isset($_POST['am_bullet_2'])) {
        $product->details['am_bullet_2']->set($_POST['am_bullet_2']);
    }
    
    if (isset($_POST['am_bullet_3'])) {
        $product->details['am_bullet_3']->set($_POST['am_bullet_3']);
    }
    
    if (isset($_POST['am_bullet_4'])) {
        $product->details['am_bullet_4']->set($_POST['am_bullet_4']);
    }
    
    if (isset($_POST['am_bullet_5'])) {
        $product->details['am_bullet_5']->set($_POST['am_bullet_5']);
    }
    
    if (isset($_POST['am_description'])) {
        $product->details['am_description']->set($_POST['am_description']);
    }
}

function add_chn_shopify($product) {
    if (isset($_POST['shopify_title'])) {
        $product->details['shopify_title']->set($_POST['shopify_title']);
    }
    
    if (isset($_POST['shopify_description'])) {
        $product->details['shopify_description']->set($_POST['shopify_description']);
    }
}

function add_variation($product) {
    $i = 0;
    $variations = array();
    
    
    foreach (range(0, getNumberOfVariationsInPost()) as $x) {
        if (array_key_exists($i, $product->variations)) {
            $variation = $product->variations[$i];
        } else {
            $variation = new NewVariation($product);
        }
        
        $filled = false;
        
        foreach ($variation->details as $detail=>$value) {
            
            if (isset($_POST[$detail . $i])) {
                $variation->details[$detail]->set($_POST[$detail . $i]);
                if ($_POST[$detail . $i] != ''){
                    $filled = true;
                }                
            }
            
            if (isset($_POST['int_shipping' . $i])) {
                $variation->details['int_shipping']->set('TRUE');
            } else {
                $variation->details['int_shipping']->set('FALSE');
            }
            
            if (isset($_POST['vat_free' . $i])) {
                $variation->details['vat_free']->set('TRUE');
            } else {
                $variation->details['vat_free']->set('FALSE');
            }
            
        }
        
        if ($filled == true) {
            $variations[] = $variation;
        }
        
        $i++;
    }
    
    
    $product->variations = array();
    $product->variations = $variations;
    
    
    foreach ($product->keyFields as $varType => $varValue){
        if($_POST['var_' . $varType] == 'true') {
            $product->keyFields[$varType] = true;
        } else {
            $product->keyFields[$varType] = false;
        }
    }
    
    foreach(getExtendedProperties() as $extendedProperty) {
        $match = true;
        $value = $product->variations[0]->details[$extendedProperty['field_name']]->value;
        foreach($product->variations as $variation) {
            if ($variation->details[$extendedProperty['field_name']]->value != $value) {
                $match = false;
            }
        }
        if ($match == true) {
            $product->details[$extendedProperty['field_name']]->set($product->variations[0]->details[$extendedProperty['field_name']]->value);
        }
    }
    
    $max_weight = 0;
    
    foreach ($product->variations as $variation) {
        if ($variation->details['weight']->value > $max_weight){
            $max_weight = $variation->details['weight']->value;
        }
        $intPostagePrices = get_international_shipping($variation->details['weight']->value);
        $variation->details['shipping_fr']->set($intPostagePrices['fr']);
        $variation->details['shipping_de']->set($intPostagePrices['de']);
        $variation->details['shipping_eu']->set($intPostagePrices['eu']);
        $variation->details['shipping_usa']->set($intPostagePrices['usa']);
        $variation->details['shipping_aus']->set($intPostagePrices['aus']);
        $variation->details['shipping_row']->set($intPostagePrices['row']);
    }
    
    $intPostagePrices = get_international_shipping($max_weight);
    $product->details['shipping_fr']->set($intPostagePrices['fr']);
    $product->details['shipping_de']->set($intPostagePrices['de']);
    $product->details['shipping_eu']->set($intPostagePrices['eu']);
    $product->details['shipping_usa']->set($intPostagePrices['usa']);
    $product->details['shipping_aus']->set($intPostagePrices['aus']);
    $product->details['shipping_row']->set($intPostagePrices['row']);
}

?>