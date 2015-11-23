<?php
namespace STCAdmin;

class Forms {

    public function addBasicInfo($product, $postData)
    {
        if (isset($postData['item_title'])) {
            $product->details['item_title']->set($postData['item_title']);
        }

        if (isset($postData['ebay_title'])) {
            $ebay_title = $postData['ebay_title'];
            if (strlen($ebay_title) > 0) {
                $product->details['ebay_title']->set($postData['ebay_title']);
            } else {
                $product->details['ebay_title']->set($postData['item_title']);
            }
        }

        if (isset($postData['var_type'])) {
            $product->details['var_type']->set(true);
        } else {
            $product->details['var_type']->set(false);
            $product->variations = [];
        }

        if (isset($postData['department'])) {
            $product->details['department']->set($postData['department']);
        }

        if (isset($postData['brand'])) {
            $product->details['brand']->set($postData['brand']);
        }

        if (isset($postData['manufacturer'])) {
            $product->details['manufacturer']->set($postData['manufacturer']);
        }

        if (isset($postData['shipping_method'])) {
            $product->details['shipping_method']->set($postData['shipping_method']);
        }

        if (isset($postData['short_description'])) {
            $product->details['short_description']->set($postData['short_description']);
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

    public function addExtendedProperties($product, $postData)
    {
        if (isset($postData['weight'])) {
            $product->details['weight']->set($postData['weight']);
        }

        if (isset($postData['location'])) {
            $product->details['location']->set($postData['location']);
        }

        if (isset($postData['mpn'])) {
            $product->details['mpn']->set($postData['mpn']);
        }

        if (isset($postData['int_shipping'])) {
            $product->details['int_shipping']->set('TRUE');
        } else {
            $product->details['int_shipping']->set('FALSE');
        }

        if (isset($postData['vat_free'])) {
            $product->details['vat_free']->set('TRUE');
        } else {
            $product->details['vat_free']->set('FALSE');
        }

        if (isset($postData['retail_price'])) {
            $product->details['retail_price']->set($postData['retail_price']);
        }

        if (isset($postData['purchase_price'])) {
            $product->details['purchase_price']->set($postData['purchase_price']);
        }

        if (isset($postData['shipping_price'])) {
            $product->details['shipping_price']->set($postData['shipping_price']);
        }

        if (isset($postData['barcode'])) {
            $product->details['barcode']->set($postData['barcode']);
        }

        if (isset($postData['height'])) {
            $product->details['height']->set($postData['height']);
        }

        if (isset($postData['width'])) {
            $product->details['width']->set($postData['width']);
        }

        if (isset($postData['depth'])) {
            $product->details['depth']->set($postData['depth']);
        }

        if (isset($postData['material'])) {
            $product->details['material']->set($postData['material']);
        }

        if (isset($postData['age'])) {
            $product->details['age']->set($postData['age']);
        }

        if (isset($postData['design'])) {
            $product->details['design']->set($postData['design']);
        }

        if (isset($postData['shape'])) {
            $product->details['shape']->set($postData['shape']);
        }

        if (isset($postData['texture'])) {
            $product->details['texture']->set($postData['texture']);
        }

        if (isset($postData['style'])) {
            $product->details['style']->set($postData['style']);
        }

        if (isset($postData['colour'])) {
            $product->details['colour']->set($postData['colour']);
        }

        if (isset($postData['size'])) {
            $product->details['size']->set($postData['size']);
        }

        if (isset($postData['quantity'])) {
            $product->details['quantity']->set($postData['quantity']);
        }
        $shippingLookup = new CSV\InternationalShippingLookup();
        $intPostagePrices = $shippingLookup->getInternationalShipping($product->details['weight']->value);
        $product->details['shipping_fr']->set($intPostagePrices['fr']);
        $product->details['shipping_de']->set($intPostagePrices['de']);
        $product->details['shipping_eu']->set($intPostagePrices['eu']);
        $product->details['shipping_usa']->set($intPostagePrices['usa']);
        $product->details['shipping_aus']->set($intPostagePrices['aus']);
        $product->details['shipping_row']->set($intPostagePrices['row']);
    }

    public function addVariation($product, $variationDetails)
    {
        foreach ($product->variations as $variationNumber => $variation) {
            foreach ($variationDetails[$variationNumber] as $detail => $value) {
                $variation->details[$detail]->set($value);
            }
        }

        $max_weight = 0;
        $shippingLookup = new CSV\InternationalShippingLookup();
        foreach ($product->variations as $variation) {
            if ($variation->details['weight']->value > $max_weight) {
                $max_weight = $variation->details['weight']->value;
            }

            $intPostagePrices = $shippingLookup->getInternationalShipping($variation->details['weight']->value);
            $variation->details['shipping_fr']->set($intPostagePrices['fr']);
            $variation->details['shipping_de']->set($intPostagePrices['de']);
            $variation->details['shipping_eu']->set($intPostagePrices['eu']);
            $variation->details['shipping_usa']->set($intPostagePrices['usa']);
            $variation->details['shipping_aus']->set($intPostagePrices['aus']);
            $variation->details['shipping_row']->set($intPostagePrices['row']);
        }

        $intPostagePrices = $shippingLookup->getInternationalShipping($max_weight);
        $product->details['shipping_fr']->set($intPostagePrices['fr']);
        $product->details['shipping_de']->set($intPostagePrices['de']);
        $product->details['shipping_eu']->set($intPostagePrices['eu']);
        $product->details['shipping_usa']->set($intPostagePrices['usa']);
        $product->details['shipping_aus']->set($intPostagePrices['aus']);
        $product->details['shipping_row']->set($intPostagePrices['row']);
    }

    public function echoInput($field, $product, $number = '')
    {
        if ($product != null) {
            $value = $product->details[$field['field_name']]->text;
        } else {
            $value = '';
        }

        if ($field['field_type'] == 'file multiple') {
            $name = substr($field['field_name'], 0, -2) . $number . '[]';
            echo "<input id={$field['field_name']} ";
            echo "name={$name} type=file multiple";
            echo " />";

        } elseif ($field['field_type'] == 'text') {
            echo "<input id='" . $field['field_name'] . "' name='" . $field['field_name'] . $number . "' type='text'";
            if ((!is_numeric($value)) or (is_numeric($value) and $value != 0)) {
                echo " value='" . htmlspecialchars($value, ENT_QUOTES) . "' ";
            }
            echo "size='" . $field['size'] . "' ";
            if ($field['required'] == true) {
                echo "required ";
            }
            echo "/>\n";

        } elseif ($field['field_type'] == 'checkbox') {
            echo "<input name={$field['field_name']}{$number} type={$field['field_type']} ";
            if (($value == true) && (!($value == 'FALSE'))) {
                echo "checked ";
            }
            echo "/>";

        } elseif ($field['field_type'] == 'textarea') {
            echo "<textarea rows=4 cols=45 id={$field['field_name']} name={$field['field_name']}{$number} ";
            if ($field['required'] == true) {
                echo "required ";
            }
            echo ">";
            echo $value;
            echo "</textarea>";
        } else {
            echo "<input name={$field['field_name']} type={$field['field_type']} size={$field['size']} />\n";
        }
    }
}
