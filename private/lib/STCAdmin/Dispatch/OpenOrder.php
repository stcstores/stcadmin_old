<?php
namespace STCAdmin\Dispatch;

class OpenOrder
{
    public function __construct($order_data)
    {
        $this->guid = $order_data['OrderId'];
        $this->order_number = $order_data['NumOrderId'];
        $this->customer_name = $order_data['CustomerInfo']['Address']['FullName'];
        $this->printed = $order_data['GeneralInfo']['InvoicePrinted'];
        $this->postage_service = $order_data['ShippingInfo']['PostalServiceName'];
        $this->date_recieved = substr($order_data['GeneralInfo']['ReceivedDate'], 0, 10);
        $this->time_recieved = substr($order_data['GeneralInfo']['ReceivedDate'], 11);
        $this->items = array();
        foreach ($order_data['Items'] as $item_data) {
            $this->items[] = new OrderItem($item_data);
        }
        $this->department = $this->getOrderDepartment();
    }

    public function getOrderDepartment()
    {
        if (count($this->items) > 0) {
            $department = $this->items[0]->department;
            foreach ($this->items as $item) {
                if ($item->department != $department) {
                    $department = 'Mixed';
                    break;
                }
            }
        } else {
            $department = 'None';
        }
        return $department;
    }
}
