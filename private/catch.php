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
        $product->details['shipping_methods']->set($_POST['shipping_methods']);
    }
    
    if (isset($_POST['short_description'])) {
        $product->details['short_description']->set($_POST['short_description']);
    }
    
    return $product;
}

function add_extended_properties($product) {
    if (isset($_POST['weight'])) {
        $product->details['weight']->set($_POST['weight']);
    }
    
    if (isset($_POST['retail_price'])) {
        $product->details['retail_price']->set($_POST['retail_price']);
    }
    
    if (isset($_POST['purchase_price'])) {
        $product->details['purchase_price']->set($_POST['purchase_price']);
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
    
    if (isset($_POST['style'])) {
        $product->details['style']->set($_POST['style']);
    }
    
    if (isset($_POST['colour'])) {
        $product->details['colour']->set($_POST['colour']);
    }
    
    if (isset($_POST['size'])) {
        $product->details['size']->set($_POST['size']);
    }
}

function add_chn_ebay($product) {
    if (isset($_POST['ebay_title'])) {
        $product->details['ebay_title']->set($_POST['ebay_title']);
    }
    
    if (isset($_POST['ebay_price'])) {
        $product->details['ebay_price']->set($_POST['ebay_price']);
    }
    
    if (isset($_POST['ebay_description'])) {
        $product->details['ebay_description']->set($_POST['ebay_description']);
    }
}

function add_chn_amazon($product) {
    if (isset($_POST['am_title'])) {
        $product->details['am_title']->set($_POST['am_title']);
    }
    
    if (isset($_POST['am_price'])) {
        $product->details['am_price']->set($_POST['am_price']);
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
    
    if (isset($_POST['shopify_price'])) {
        $product->details['shopify_price']->set($_POST['shopify_price']);
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
                //echo $detail . $i;
                $variation->details[$detail]->set($_POST[$detail . $i]);
                if ($_POST[$detail . $i] != ''){
                    $filled = true;
                }
                
            }
            
        }
        
        if ($filled == true) {
            $variations[] = $variation;
        }
        
        $i++;
    }
    
    
    $product->variations = array();
    $product->variations = $variations;
    
    $_SESSION['new_product'] = $product;
}

?>