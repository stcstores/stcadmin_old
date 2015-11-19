<?php
namespace STCAdmin\Product;

class Variation  extends Product {
    public function __construct($product, $api)
    {
        $this->product = $product;
        parent::__construct($product->database, $api);
    }

    public function createDetails()
    {
        $this->details['sku'] = new ProductDetails\ProductDetail('sku', $this);
        $this->details['guid'] = new ProductDetails\ProductDetail('guid', $this);
        $this->details['var_append'] = new ProductDetails\ProductDetail('var_append', $this);

        //basic info
        $this->details['var_name'] = new ProductDetails\ProductDetail('item_title', $this);
        $this->details['department'] = new ProductDetails\ProductDetail('deparmtent', $this);
        $this->details['brand'] = new ProductDetails\ProductDetail('brand', $this);
        $this->details['manufacturer'] = new ProductDetails\ProductDetail('manufacturer', $this);
        $this->details['short_description'] = new ProductDetails\ProductDetail('short_description', $this);

        //extended properties
        $this->details['mpn'] = new ProductDetails\ProductDetail('mpn', $this);
        $this->details['location'] = new ProductDetails\ProductDetail('location', $this);
        $this->details['weight'] = new ProductDetails\NumericDetail('weight', $this);
        $this->details['int_shipping'] = new ProductDetails\BooleanDetail('int_shipping', $this);
        $this->details['vat_free'] = new ProductDetails\BooleanDetail('vat_free', $this);
        $this->details['retail_price'] = new ProductDetails\ProductDetail('retail_price', $this);
        $this->details['purchase_price'] = new ProductDetails\ProductDetail('purchase_price', $this);
        $this->details['shipping_price'] = new ProductDetails\ProductDetail('shipping_price', $this);
        $this->details['shipping_method'] = new ProductDetails\ProductDetail('shipping_method', $this);
        $this->details['barcode'] = new ProductDetails\ProductDetail('barcode', $this);
        $this->details['size'] = new ProductDetails\ProductDetail('size', $this);
        $this->details['colour'] = new ProductDetails\ProductDetail('colour', $this);
        $this->details['height'] = new ProductDetails\NumericDetail('height', $this);
        $this->details['width'] = new ProductDetails\NumericDetail('width', $this);
        $this->details['depth'] = new ProductDetails\NumericDetail('depth', $this);
        $this->details['material'] = new ProductDetails\ProductDetail('material', $this);
        $this->details['age'] = new ProductDetails\ProductDetail('age', $this);
        $this->details['design'] = new ProductDetails\ProductDetail('design', $this);
        $this->details['shape'] = new ProductDetails\ProductDetail('shape', $this);
        $this->details['texture'] = new ProductDetails\ProductDetail('texture', $this);
        $this->details['style'] = new ProductDetails\ProductDetail('style', $this);
        $this->details['quantity'] = new ProductDetails\NumericDetail('quantity', $this);

        //international shipping
        $this->details['shipping_fr'] = new ProductDetails\ProductDetail('shipping_fr', $this);
        $this->details['shipping_de'] = new ProductDetails\ProductDetail('shipping_de', $this);
        $this->details['shipping_eu'] = new ProductDetails\ProductDetail('shipping_eu', $this);
        $this->details['shipping_usa'] = new ProductDetails\ProductDetail('shipping_usa', $this);
        $this->details['shipping_aus'] = new ProductDetails\ProductDetail('shipping_aus', $this);
        $this->details['shipping_row'] = new ProductDetails\ProductDetail('shipping_row', $this);

        //chn_ebay
        $this->details['ebay_title'] = new ProductDetails\ProductDetail('ebay_title', $this);
    }

    public function getLinnTitle()
    {
        $product_title = $this->product->details['item_title']->text;
        $location = $this->details['location']->text;
        $mpn = $this->details['mpn']->text;
        $var_append = $this->details['var_append']->text;
        $item_title = '';
        if (strlen($location) > 0) {
            $item_title = $item_title . $location . ' ';
        }
        if (strlen($mpn) > 0) {
            $item_title = $item_title . $mpn . ' ';
        }
        $item_title = $item_title . $product_title . ' ';
        $keyFields = $this->product->keyFields;
        foreach ($keyFields as $field => $isKey) {
            if ($isKey) {
                $item_title = $item_title . '{ ';
                $item_title = $item_title . $this->details[$field]->text;
                $item_title = $item_title . ' } ';
            }
        }
        if ($var_append != '') {
            $item_title = $item_title . $var_append;
        }
        return trim($item_title);
    }
}
