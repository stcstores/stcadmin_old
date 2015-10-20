<?php


class InventoryItem{
    
    
    function __construct($api, $stock_id=null) {
        $this -> api = $api;
        if ($stock_id != null) {
            $this -> stock_id = $stock_id;
        } else {
            $this -> get_stock_id();
        }
        $this -> json = null;
        $this -> inventory = null;
        $this -> sku = '';
        $this -> title = '';
        $this -> purchase_price = 0;
        $this -> retail_price = 0;
        $this -> barcode = '';
        $this -> category_id = '';
        $this -> category = '';
        $this -> depth = '';
        $this -> height = '';
        $this -> package_group_id = '';
        $this -> package_group = '';
        $this -> postage_service_id = '';
        $this -> postage_service = '';
        $this -> tax_rate = 0;
        $this -> variation_group_name = '';
        $this -> weight = 0;
        $this -> width = 0;
        $this -> quantity = 0;
        $this -> meta_data = '';
        $this -> extended_properties = new _ExtendedProperties($this);
    }
        
    function __toString(){
        return $this -> sku . ': ' . $this -> title;
    }
        
    function get_stock_id() {
        $this -> stock_id = createGUID();
    }
        
    function create_sku() {
        $this -> sku = $this -> api -> get_new_sku();
    }
        
    function get_create_inventoryItem_dict() {
        $inventoryItem = array();
        $inventoryItem['ItemNumber'] = $this -> sku;
        $inventoryItem['ItemTitle'] = $this -> title;
        $inventoryItem['BarcodeNumber'] = $this -> barcode;
        $inventoryItem['PurchasePrice'] = $this -> purchase_price;
        $inventoryItem['RetailPrice'] = $this -> retail_price;
        $inventoryItem['Quantity'] = $this -> quantity;
        $inventoryItem['TaxRate'] = $this -> tax_rate;
        $inventoryItem['StockItemId'] = $this -> stock_id;
        return $inventoryItem;
    }
        
    function get_inventoryItem_dict() {
        $inventoryItem = array();
        $inventoryItem['ItemNumber'] = $this -> sku;
        $inventoryItem['ItemTitle'] = $this -> title;
        $inventoryItem['BarcodeNumber'] = $this -> barcode;
        $inventoryItem['PurchasePrice'] = $this -> purchase_price;
        $inventoryItem['RetailPrice'] = $this -> retail_price;
        $inventoryItem['Quantity'] = $this -> quantity;
        $inventoryItem['TaxRate'] = $this -> tax_rate;
        $inventoryItem['StockItemId'] = $this -> stock_id;
        $inventoryItem['VariationGroupName'] = $this -> variation_group_name;
        $inventoryItem['MetaData'] = $this -> meta_data;
        $inventoryItem['CategoryId'] = $this -> category_id;
        $inventoryItem['PackageGroupId'] = $this -> package_group_id;
        $inventoryItem['PostalServiceId'] = $this -> postage_service_id;
        $inventoryItem['Weight'] = $this -> weight;
        $inventoryItem['Width'] = $this -> width;
        $inventoryItem['Depth'] = $this -> depth;
        $inventoryItem['Height'] = $this -> height;
        return $inventoryItem;
    }
        
    function create_item() {
        $inventoryItem = $this -> get_create_inventoryItem_dict();
        $request_url = $this -> api -> server . '/api/Inventory/AddInventoryItem';
        $data = array();
        $data['inventoryItem'] = json_encode($inventoryItem);
        
        return $this -> api -> request($request_url, $data);
    }
        
    function update_item() {
        $inventoryItem = $this -> get_inventoryItem_dict();
        $request_url = $this -> api -> server . '/api/Inventory/UpdateInventoryItem';
        $data = array();
        $data['inventoryItem'] = json_encode($inventoryItem);
        return $this -> api -> request($request_url, $data);
    }
        
    function update_all() {
        $this -> update_item();
        $this -> extended_properties -> update();
    }
        
    function load_extended_properties() {
        $this -> extended_properties -> load();
    }
        
    function get_extended_properties_dict() {
        $properties = array();
        foreach ($this -> extended_properties as $prop) {
            if ($prop -> delete == false) {
                $properties[$prop -> name] = $prop -> value;
            }
        }
        return $properties;
    }
        
    function get_extended_properties_list() {
        $properties = array();
        foreach ($this -> extended_properties as $prop) {
            if ($prop -> delete == false) {
                $new_prop = array();
                $new_prop['name'] = $prop -> name;
                $new_prop['value'] = $prop -> value;
                $new_prop['type'] = $prop -> type;
                $new_prop['guid'] = $prop -> guid;
            }
            $properties[] = $new_prop;
        }
        return $properties;
    }
        
    function create_extended_property($name='', $value='', $property_type='Attribute') {
        $prop = _ExtendedProperty();
        $prop -> name = $name;
        $prop -> value = $value;
        $prop -> type = $property_type;
        $this -> extended_properties[] = prop;
    }
        
    function add_imagefilepath($filepath) {
        $upload_response = $this -> api -> upload_image($filepath);
        $image_guid = $upload_response[0]['FileId'];
        $add_url = $this -> api -> server . '/api/Inventory/UploadImagesToInventoryItem';
        $add_data = array();
        $add_data['inventoryItemId'] = $this -> stock_id;
        $add_data['imageIds'] = json_encode([$image_guid]);
        $add_response = $this -> api -> request($add_url, $add_data);
        return $add_response;
    }
}


class _ExtendedProperties {
    
    function __construct($item, $load=true) {
        $this -> item = $item;
        $this -> extended_properties = array();
        if ($load == true) {
            $this -> load();
        }
    }
        
    //def __getitem__key):
    //    if type(key) == int:
    //        return $this -> extended_properties[key]
    //    elif type(key) == str:
    //        for prop in $this -> extended_properties:
    //            if prop.name == key:
    //                return prop
    //    
    //def __iter__():
    //    for prop in $this -> extended_properties:
    //        yield prop
    //        
    //def __len__():
    //    return len($this -> extended_properties)
    
    function get_property_by_name($name) {
        foreach ($this->extended_properties as $property) {
            if ($property -> name == $name) {
                return $property;
            }
        }
    }
    
    function append($extended_property) {
        $this -> extended_properties[$extended_property -> value] = $extended_property;
    }
        
    function load() {
        $response = $this -> item -> api -> get_inventory_item_extended_properties($this -> item -> stock_id);
        foreach ($response as $property) {
            $this -> add($json=$property);
        }
    }
        
    function add($json) {
        $this -> extended_properties[] = new _ExtendedProperty($this -> item, $json=$json);
    }
        
    function create ($name='', $value='', $property_type='Attribute') {
        $prop = $_ExtendedProperty($this -> item);
        $prop -> name = $name;
        $prop -> value = $value;
        $prop -> type = $property_type;
        $this -> extended_properties[] = $prop;
        return $prop;
    }
        
    function create_on_server ($name='', $value='', $property_type='Attribute') {
        $prop = $this -> create($name=$name, $value=$value, $property_type=$property_type);
        $prop -> create();
    }
        
    function upload_new() {
        $new_properties = array();
        foreach ($extended_properteis as $prop) {
            if ($prop -> on_server == false) {
                $new_properties[] = $prop;
            }
        }
        if (count($new_properties) > 0) {
            $item_arrays = array();
            foreach ($new_properties as $prop) {
                $item_arrays[] = $prop -> get_json();
            }
            $data = array();
            $data['inventoryItemExtendedProperties'] = json_encode($item_arrays);
            $api = $this -> item -> api;
            $url = $api -> server . '/api/Inventory/CreateInventoryItemExtendedProperties';
            $response = $api -> request($url, $data);
            return $response;
        }
    }
        
    function update_existing() {
        $new_properties = array();
        foreach ($extended_properties as $prop) {
            if ($prop -> on_server == true) {
                $new_properties[] = $prop;
            }
        }
        if (count($new_properties) > 0) {
            $item_arrays = array();
            foreach ($new_properties as $prop) {
                $item_arrays[] = $prop -> get_json();
            }
            $data = array();
            $data['inventoryItemExtendedProperties'] = json_encode($item_arrays);
            
            $api = $this -> item -> api;
            $url = $api -> server . '/api/Inventory/UpdateInventoryItemExtendedProperties';
            $response = $api -> $request($url, $data);
            return $response;
        }
    }
        
    function remove_deleted() {
        $api = $this -> item -> api;
        $items_to_delete = array();
        foreach ($this -> extended_properties as $prop) {
            if ($prop -> delete == true) {
                $items_to_delete[] = $prop -> guid;
            }
        }
        if (count($items_to_delete) > 0) {
            $data = array();
            $data['inventoryItemId'] = $this -> item -> stock_id;
            $data['inventoryItemExtendedPropertyIds'] = json_encode($items_to_delete);
            $url = $api -> server . '/api/Inventory/DeleteInventoryItemExtendedProperties';
            $response = $api -> request($url, $data);
            return $response;
        }
    }
        
    function update() {
        $this -> upload_new();
        $this -> update_existing();
        $this -> remove_deleted();
    }
}
    

class _ExtendedProperty {
    
    
    function __construct ($item, $json=null) {
        $this -> item = $item;
        $this -> type = '';
        $this -> value = '';
        $this -> name = '';
        $this -> on_server = false;
        $this -> delete = false;
        
        if ($json != null) {
            $this -> load_from_json($json);
            $this -> on_server = true;
        }
        else {
            $this -> guid = createGUID();
            $this -> on_server = false;
        }
    }
            
    function load_from_json ($json) {
        $this -> type = $json['PropertyType'];
        $this -> value = $json['PropertyValue'];
        $this -> name = $json['ProperyName'];
        $this -> guid = $json['pkRowId'];
    }
        
    function get_json() {
        $data = array();
        $data['pkRowId'] = $this -> guid;
        $data['fkStockItemId'] = $this -> item -> stock_id;
        $data['ProperyName'] = $this -> name;
        $data['PropertyValue'] = $this -> value;
        $data['PropertyType'] = $this -> type;
        return $data;
    }
        
    function update() {
        $api = $this -> item -> api;
        $url = $api -> server . '/api/Inventory/UpdateInventoryItemExtendedProperties';
        $data = array();
        $data['inventoryItemExtendedProperties'] = json_encode([$this -> get_json()]);
        $response = $api -> request($url, $data);
        return $response;
    }
        
    function create() {
        $api = $this -> item -> api;
        $url = $api -> server . '/api/Inventory/CreateInventoryItemExtendedProperties';
        $data = array();
        $data['inventoryItemExtendedProperties'] -> json_encode([$this -> get_json()]);
        $response = $api -> request($url, $data);
        return $response;
    }
        
    function remove() {
        $this -> delete = true;
    }
        
    function delete_from_server() {
        $api = $this -> item -> api;
        $data = array();
        $data['inventoryItemId'] = $this -> item -> stock_id;
        $data['inventoryItemExtendedPropertyIds'] -> json_encode([$this -> guid]);
        
        $url = $api -> server . '/api/Inventory/DeleteInventoryItemExtendedProperties';
        $response = $api -> request($url, $data);
        return $response;
    }
}