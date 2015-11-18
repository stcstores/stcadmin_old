<?php
namespace STCAdmin;

class BooleanDetail extends ProductDetail {
    public function set($value)
    {
        if (($value == true) || (ucwords($value) == 'TRUE')) {
            $this->value = true;
            $this->text = 'TRUE';
        } elseif (($value == false) || (ucwords($value) == 'FALSE')) {
            $this->value = false;
            $this->text = 'FALSE';
        }
    }
}
