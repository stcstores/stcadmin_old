<?php

class NewProduct{
    function __construct() {      
        $this->errors = $this->createFieldsArray();
        $this->variations = array();
        $this->details['sku'] = new SKU('sku', $this);
        
        //basic info
        $this->details['item_title'] = new ProductDetail('item_title', $this);
        $this->details['var_type'] = new ProductDetail('var_type', $this);
        $this->details['department'] = new ProductDetail('deparmtent', $this);
        $this->details['brand'] = new ProductDetail('brand', $this);
        $this->details['manufacturer'] = new ProductDetail('manufacturer', $this);
        $this->details['short_description'] = new ProductDetail('short_description', $this);
        
        //extended properties
        $this->details['weight'] = new ProductDetail('weight', $this);
        $this->details['retail_price'] = new ProductDetail('retail_price', $this);
        $this->details['purchase_price'] = new ProductDetail('purchase_price', $this);
        $this->details['barcode'] = new ProductDetail('barcode', $this);
        $this->details['shipping_methods'] = new ProductDetail('shipping_method', $this);
        $this->details['size'] = new ProductDetail('size', $this);
        $this->details['colour'] = new ProductDetail('colour', $this);
        $this->details['height'] = new ProductDetail('height', $this);
        $this->details['width'] = new ProductDetail('width', $this);
        $this->details['depth'] = new ProductDetail('depth', $this);
        $this->details['material'] = new ProductDetail('material', $this);
        $this->details['style'] = new ProductDetail('style', $this);
        
        //chn_ebay
        $this->details['ebay_title'] = new ProductDetail('ebay_title', $this);
        $this->details['ebay_price'] = new ProductDetail('ebay_price', $this);
        $this->details['ebay_description'] = new ProductDetail('ebay_description', $this);
        
        //ech_amazon
        $this->details['am_title'] = new ProductDetail('am_title', $this);
        $this->details['am_price'] = new ProductDetail('am_price', $this);
        $this->details['am_bullet_1'] = new ProductDetail('am_bullet_1', $this);
        $this->details['am_bullet_2'] = new ProductDetail('am_bullet_2', $this);
        $this->details['am_bullet_3'] = new ProductDetail('am_bullet_3', $this);
        $this->details['am_bullet_4'] = new ProductDetail('am_bullet_4', $this);
        $this->details['am_bullet_5'] = new ProductDetail('am_bullet_5', $this);
        $this->details['am_description'] = new ProductDetail('am_description', $this);
        
        //chn_shopify
        $this->details['shopify_title'] = new ProductDetail('ekm_title', $this);
        $this->details['shopify_price'] = new ProductDetail('ekm_price', $this);
        $this->details['shopify_description'] = new ProductDetail('ekm_description', $this);
        
    }
    
    function setTitle($title) {
        $this->item_title = $title;
        $this->values['item_title'] = $title;
    }
    
    function createFieldsArray() {
        $array = array();
        $fields = getDatabaseColumn('new_product_form_field', 'field_name');
        foreach ($fields as $field) {
            $array[$field] = '';
        }
        return $array;
    }
}

class NewVariation  extends NewProduct {
    function __construct($product) {
        $this->product = $product;
        $this->errors = $this->createFieldsArray();
        $this->details['sku'] = new SKU('sku', $this);
        
        //basic info
        $this->details['var_name'] = new ProductDetail('item_title', $this);
        $this->details['department'] = new ProductDetail('deparmtent', $this);
        $this->details['department']->set($product->details['department']->value);
        $this->details['brand'] = new ProductDetail('brand', $this);
        $this->details['manufacturer'] = new ProductDetail('manufacturer', $this);
        $this->details['manufacturer']->set($product->details['manufacturer']->value);
        
        //extended properties
        $this->details['weight'] = new ProductDetail('weight', $this);
        $this->details['retail_price'] = new ProductDetail('retail_price', $this);
        $this->details['purchase_price'] = new ProductDetail('purchase_price', $this);
        $this->details['barcode'] = new ProductDetail('barcode', $this);
        $this->details['size'] = new ProductDetail('size', $this);
        $this->details['colour'] = new ProductDetail('colour', $this);
        $this->details['height'] = new ProductDetail('height', $this);
        $this->details['width'] = new ProductDetail('width', $this);
        $this->details['depth'] = new ProductDetail('depth', $this);
        $this->details['material'] = new ProductDetail('material', $this);
        $this->details['style'] = new ProductDetail('style', $this);
    }
}

class ProductDetail {
    function __construct($name, $product) {
        $this->name = $name;
        $this->product = $product;
        $this->value = NULL;
        $this->text = NULL;
    }
    
    function set($value) {
        $this->value = $value;
        $this->text = (string)$value;
    }
}

class SKU extends ProductDetail {
    function __construct($name, $product) {
        parent::__construct($name, $product);
        $this->set(generateSku());
    }
}

class Has_Variations extends ProductDetail {
    function __construct($name, $product) {
        parent::__construct($name, $product);
        $this->value = array();
        $this->text = 'unset';
    }
    
    function set($value) {
        if ($value == true){
            $this->value = true;
            $this->text = 'true';
        } elseif ($value == false) {
            $this->value = false;
            $this->text = 'false';
        }
    }
}

function error_check_basic_info($product) {
    $errors = false;
        foreach ($product->errors as $field) {
            if (count($field) > 0) {
                return true;
            }
        }
}