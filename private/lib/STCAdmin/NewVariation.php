<?php
namespace STCAdmin;

class NewVariation  extends Variation {
    public function __construct($product, $api, $database)
    {
        parent::__construct($product, $api, $database);
        $this->setDetails();
    }

    public function setDetails()
    {
        $this->details['sku']->set($this->api->get_new_sku());
        $this->details['guid']->set($this->createGUID());
        $this->details['department']->set($this->product->details['department']->value);
        $this->details['brand']->set($this->product->details['brand']->value);
        $this->details['manufacturer']->set($this->product->details['manufacturer']->value);
        $this->details['short_description']->set($this->product->details['short_description']->value);
        $this->details['int_shipping'] -> set('TRUE');
        $this->details['shipping_price']->set($this->product->details['shipping_price']->value);
        $this->details['shipping_method']->set($this->product->details['shipping_method']->value);
        $this->details['ebay_title']->set($this->product->details['ebay_title']->value);
    }
}
