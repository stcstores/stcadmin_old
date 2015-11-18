<?php
namespace STCAdmin\ProductDetails;

class ProductDetail {
    public function __construct($name, $product)
    {
        $this->name = $name;
        $this->product = $product;
        $this->value = null;
        $this->text = '';
        $this->api_value = '';
    }

    public function set($value)
    {
        $this->value = $value;
        $this->text = (string)$value;
        $this->api_value = (string)$value;
    }
}
