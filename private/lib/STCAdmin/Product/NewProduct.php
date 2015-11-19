<?php
namespace STCAdmin\Product;

class NewProduct extends Product {

    public function __construct($database, $api)
    {
        parent::__construct($database, $api);

        $this->details['sku']->set($api->get_new_sku());
        $this->details['guid']->set($this->createGUID());
    }
}
