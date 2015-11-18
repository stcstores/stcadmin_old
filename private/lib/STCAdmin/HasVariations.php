<?php
namespace STCAdmin;

class Has_Variations extends ProductDetail {
    public function __construct($name, $product)
    {
        parent::__construct($name, $product);
        $this->value = array();
        $this->text = 'unset';
    }

    public function set($value)
    {
        if ($value == true) {
            $this->value = true;
            $this->text = 'true';
        } elseif ($value == false) {
            $this->value = false;
            $this->text = 'false';
        }
    }
}
