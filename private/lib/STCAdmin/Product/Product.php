<?php
namespace STCAdmin\Product;

class Product {
    public function __construct($database, $api)
    {
        $this->database = $database;
        $this->api = $api;
        $this->variations = array();
        $this->images = array();
        $this->createDetails();
        $this->createKeyFields();
    }

    public function createGUID()
    {
        $guid = shell_exec('python ' . dirname(__FILE__) . '/get_uuid.py');
        $guid = str_replace(array("\r", "\n"), '', $guid);
        return $guid;
    }

    private function createKeyFields()
    {
        $this->keyFields = array();
        foreach ($this->database->getKeyFields() as $keyField) {
            $this->keyFields[$keyField['field_name']] = false;
        }
    }

    public function createDetails()
    {
        $this->details['sku'] = new ProductDetails\ProductDetail('sku', $this);
        $this->details['guid'] = new ProductDetails\ProductDetail('guid', $this);
        //basic info
        $this->details['item_title'] = new ProductDetails\ProductDetail('item_title', $this);
        $this->details['ebay_title'] = new ProductDetails\ProductDetail('ebay_title', $this);
        $this->details['var_type'] = new ProductDetails\ProductDetail('var_type', $this);
        $this->details['department'] = new ProductDetails\ProductDetail('deparmtent', $this);
        $this->details['brand'] = new ProductDetails\ProductDetail('brand', $this);
        $this->details['manufacturer'] = new ProductDetails\ProductDetail('manufacturer', $this);
        $this->details['short_description'] = new ProductDetails\ProductDetail('short_description', $this);

        //extended properties
        $this->details['mpn'] = new ProductDetails\ProductDetail('mpn', $this);
        $this->details['location'] = new ProductDetails\ProductDetail('location', $this);
        $this->details['weight'] = new ProductDetails\NumericDetail('weight', $this);
        $this->details['int_shipping'] = new ProductDetails\ProductDetail('int_shipping', $this);
        $this->details['int_shipping'] -> set('TRUE');
        $this->details['vat_free'] = new ProductDetails\ProductDetail('vat_free', $this);
        $this->details['retail_price'] = new ProductDetails\ProductDetail('retail_price', $this);
        $this->details['purchase_price'] = new ProductDetails\ProductDetail('purchase_price', $this);
        $this->details['shipping_price'] = new ProductDetails\ProductDetail('shipping_price', $this);
        $this->details['barcode'] = new ProductDetails\ProductDetail('barcode', $this);
        $this->details['shipping_method'] = new ProductDetails\ProductDetail('shipping_method', $this);
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
    }

    public function setTitle($title)
    {
        $this->item_title = $title;
        $this->values['item_title'] = $title;
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
            return $this->getLinnTitleForSingleItem();
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

        if ($this->variationDetailsMatch('mpn')) {
            $mpn = $this->variations[0]->details['mpn']->text;
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
        return $match;
    }

    public function onServer()
    {
        return $this->api->sku_exists($this->details['sku']->text);
    }

    public function getVariationDetailValues()
    {
        $variationValues = array();
        foreach ($this->variations as $variation) {
            foreach ($variation->details as $key => $value) {
                $newArray[$key] = htmlspecialchars($value->text);
            }
            $variations[] = $newArray;
        }
        return $variationValues;
    }

    public function setImagePrimary($guid)
    {
        $i = 0;
        foreach ($this->images as $image) {
            if ($image->guid == $guid) {
                $newPrimeId = $i;
            }
            $i++;
        }
        $newPrimeImage = $this->images[$newPrimeId];
        unset($this->images[$newPrimeId]);
        array_unshift($this->images, $newPrimeImage);
    }

    public function addImage($guid, $thumbPath, $fullPath)
    {
        $this -> images[] = new Image($guid, $thumbPath, $fullPath);
    }

    public function removeImage($guid)
    {
        $i = 0;
        foreach ($this->images as $image) {
            if ($image->guid == $guid) {
                $idToRemove = $i;
            }
            $i++;
        }

        array_splice($this->images, $idToRemove, 1);
    }
}
