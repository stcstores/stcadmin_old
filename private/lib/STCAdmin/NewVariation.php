<?php
namespace STCAdmin;

class NewVariation  extends Variation {
    public function __construct($product, $api, $database)
    {
        parent::__construct($product, $api, $database);

        $this->details['sku']->set($api->get_new_sku());
        $this->details['guid']->set($this->createGUID());
    }
}
