<?php
namespace STCAdmin;

class Product {
    public function __construct($database, $api)
    {
        $this->database = $database;
        $this->api = $api;
        $this->errors = $this->createFieldsArray();
        $this->variations = array();


        $this->createDetails();

        $this->keyFields = array();
        foreach ($database->getKeyFields() as $keyField) {
            $this->keyFields[$keyField['field_name']] = false;
        }

        $this -> images = new Images();

    }

    public function createGUID()
    {
        $guid = shell_exec('python ' . dirname($_SERVER['DOCUMENT_ROOT']) . '/private/get_uuid.py');
        $guid = str_replace(array("\r", "\n"), '', $guid);
        return $guid;

    }

    public function createDetails()
    {
        $this->details['sku'] = new ProductDetail('sku', $this);
        $this->details['guid'] = new ProductDetail('guid', $this);
        //basic info
        $this->details['item_title'] = new ProductDetail('item_title', $this);
        $this->details['ebay_title'] = new ProductDetail('ebay_title', $this);
        $this->details['var_type'] = new ProductDetail('var_type', $this);
        $this->details['department'] = new ProductDetail('deparmtent', $this);
        $this->details['brand'] = new ProductDetail('brand', $this);
        $this->details['manufacturer'] = new ProductDetail('manufacturer', $this);
        $this->details['short_description'] = new ProductDetail('short_description', $this);

        //extended properties
        $this->details['mpn'] = new ProductDetail('mpn', $this);
        $this->details['location'] = new ProductDetail('location', $this);
        $this->details['weight'] = new NumericDetail('weight', $this);
        $this->details['int_shipping'] = new ProductDetail('int_shipping', $this);
        $this->details['int_shipping'] -> set('TRUE');
        $this->details['vat_free'] = new ProductDetail('vat_free', $this);
        $this->details['retail_price'] = new ProductDetail('retail_price', $this);
        $this->details['purchase_price'] = new ProductDetail('purchase_price', $this);
        $this->details['shipping_price'] = new ProductDetail('shipping_price', $this);
        $this->details['barcode'] = new ProductDetail('barcode', $this);
        $this->details['shipping_method'] = new ProductDetail('shipping_method', $this);
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
    }

    public function setTitle($title)
    {
        $this->item_title = $title;
        $this->values['item_title'] = $title;
    }

    public function createFieldsArray()
    {
        $array = array();
        $fields = $this->database->getColumn('new_product_form_field', 'field_name');
        foreach ($fields as $field) {
            $array[$field] = '';
        }
        return $array;
    }

    public function toHTML($string)
    {
        $lines = explode("\n", $string);
        $new_string = "";
        foreach ($lines as $line) {
            if (trim($line) == '') {
                $new_string = $new_string . "<br />\n";
            } else {
                $new_string = $new_string . "<p>" . trim($line) . "</p>\n";
            }
        }
        return $new_string;
    }

    public function getLinnTitle()
    {
        if (count($this->variations) > 1) {
            return $this->getLinnTitleForVariationParent();
        } else {
            return getLinnTitleForSingleItem();
        }
    }

    private function getLinnTitleForSingleItem()
    {
        $item_title = $this->details['item_title']->text;
        $location = $this->details['location']->text;
        $mpn = $this->details['mpn']->text;
        if (strlen($mpn) > 0) {
            $item_title = $mpn . ' ' . $item_title;
        }

        if (strlen($location) > 0) {
            $item_title = $location . ' ' . $item_title;
        }
        return $item_title;
    }

    private function getLinnTitleForVariationParent()
    {
        $item_title = $this->details['item_title']->text;

        if (variationDetailsMatch('mpn')) {
            $item_title = $mpn . ' ' . $item_title;
        }
        return $item_title;
    }

    public function variationDetailsMatch($detail)
    {
        $match = true;
        $detail_value = $this->variations[0]->details[$detail]->text;
        foreach ($this->variations as $variation) {
            $this_detail = $variation->details[$detail]->text;
            if ($this_detail == '' || $this_detail != $detail_value) {
                $match = false;
                break;
            }
        }
        return match;
    }
}
