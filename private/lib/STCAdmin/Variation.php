<?php
namespace STCAdmin;

class Variation  extends Product {
    public function __construct($product)
    {
        $this->product = $product;
        $this->errors = $this->createFieldsArray();
        $this->details['sku'] = new SKU('sku', $this);
        $this->details['guid'] = new GUID('guid', $this);
        $this->details['var_append'] = new ProductDetail('var_append', $this);

        //basic info
        $this->details['var_name'] = new ProductDetail('item_title', $this);
        $this->details['department'] = new ProductDetail('deparmtent', $this);
        $this->details['department']->set($product->details['department']->value);
        $this->details['brand'] = new ProductDetail('brand', $this);
        $this->details['brand']->set($product->details['brand']->value);
        $this->details['manufacturer'] = new ProductDetail('manufacturer', $this);
        $this->details['manufacturer']->set($product->details['manufacturer']->value);
        $this->details['short_description'] = new ProductDetail('short_description', $this);
        $this->details['short_description']->set($product->details['short_description']->value);

        //extended properties
        $this->details['mpn'] = new ProductDetail('mpn', $this);
        $this->details['location'] = new ProductDetail('location', $this);
        $this->details['weight'] = new NumericDetail('weight', $this);
        $this->details['int_shipping'] = new BooleanDetail('int_shipping', $this);
        $this->details['int_shipping'] -> set('TRUE');
        $this->details['vat_free'] = new BooleanDetail('vat_free', $this);
        $this->details['retail_price'] = new ProductDetail('retail_price', $this);
        $this->details['purchase_price'] = new ProductDetail('purchase_price', $this);
        $this->details['shipping_price'] = new ProductDetail('shipping_price', $this);
        $this->details['shipping_price']->set($product->details['shipping_price']->value);
        $this->details['shipping_method'] = new ProductDetail('shipping_method', $this);
        $this->details['shipping_method']->set($product->details['shipping_method']->value);
        $this->details['barcode'] = new ProductDetail('barcode', $this);
        $this->details['size'] = new ProductDetail('size', $this);
        $this->details['colour'] = new ProductDetail('colour', $this);
        $this->details['height'] = new NumericDetail('height', $this);
        $this->details['width'] = new NumericDetail('width', $this);
        $this->details['depth'] = new NumericDetail('depth', $this);
        $this->details['material'] = new ProductDetail('material', $this);
        $this->details['age'] = new ProductDetail('age', $this);
        $this->details['design'] = new ProductDetail('design', $this);
        $this->details['shape'] = new ProductDetail('shape', $this);
        $this->details['texture'] = new ProductDetail('texture', $this);
        $this->details['style'] = new ProductDetail('style', $this);
        $this->details['quantity'] = new NumericDetail('quantity', $this);

        //international shipping
        $this->details['shipping_fr'] = new ProductDetail('shipping_fr', $this);
        $this->details['shipping_de'] = new ProductDetail('shipping_de', $this);
        $this->details['shipping_eu'] = new ProductDetail('shipping_eu', $this);
        $this->details['shipping_usa'] = new ProductDetail('shipping_usa', $this);
        $this->details['shipping_aus'] = new ProductDetail('shipping_aus', $this);
        $this->details['shipping_row'] = new ProductDetail('shipping_row', $this);

        //chn_ebay
        $this->details['ebay_title'] = new ProductDetail('ebay_title', $this);
        $this->details['ebay_title']->set($product->details['ebay_title']->value);

        $this->keyFields = array();
        foreach (getKeyFields() as $keyField) {
            $this->keyFields[$keyField['field_name']] = false;
        }

        $this -> images = new Images();

        $this -> images = new Images();
    }
}
