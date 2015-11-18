<?php
namespace STCAdmin\ProductDetails;

class NumericDetail extends ProductDetail {
    public function __construct($name, $product)
    {
        $this->name = $name;
        $this->product = $product;
        $this->value = null;
        $this->text = '';
        $this->api_value = '0';
    }

    public function set($value)
    {
        $this->value = $value;
        if ($value > 0) {
            $this->text = (string)$value;
            $this->api_value = (string)$value;
        } else {
            $this->text = '0';
            $this->api_value = '0';
        }
    }
}
