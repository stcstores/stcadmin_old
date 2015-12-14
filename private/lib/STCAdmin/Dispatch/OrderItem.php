<?php
namespace STCAdmin\Dispatch;

class OrderItem
{
    public function __construct($item_data)
    {
        $this->guid = $item_data['ItemId'];
        $this->sku = $item_data['SKU'];
        $this->item_title = $item_data['Title'];
        $this->department = $item_data['CategoryName'];
    }
}
