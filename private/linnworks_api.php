<?php

require_once($CONFIG['inventory_item_class']);

set_time_limit ( 15000 );

class LinnworksAPI {
    
    
    function __construct($username, $password) {
        $this -> username = $username;
        $this -> password = $password;
        $this -> userID = null;
        $this -> server = null;
        
        $this -> curl = $this -> curlSetup();
        
        if (isset($_SESSION['token'])){
            $this -> token = $_SESSION['token'];
            $this -> server = $_SESSION['server'];
        } else {
            $this -> getToken();
        }
    }
    
    function curlSetup() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, dirname($_SERVER['DOCUMENT_ROOT']) . '/private/certificates/thawtePrimaryRootCA.crt');
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        return $curl;
    }
    
    function makeRequest($url, $data) {
        $curl = $this -> curl;
        $datastring = http_build_query($data);;
        curl_setopt($curl, CURLOPT_URL, $url . '?token=' . $this->token);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datastring);
        $information = curl_getinfo($curl);
        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $responseJson = json_decode($body, true);
        return $responseJson;
    }
    
    function request($url, $data=null) {
        if ($data == null) {
            $data = array();
        }
        //$data['token'] = $this -> token;
        return $this -> makeRequest($url, $data);
    }
    
    function getToken() {
        $loginURL = 'https://api.linnworks.net/api/Auth/Multilogin';
        $authURL = 'https://api.linnworks.net/api/Auth/Authorize';
        $data = array('userName' => $this -> username, 'password' => $this -> password);
        $multiLogin = $this -> makeRequest($loginURL, $data);
        $this -> userID = $multiLogin[0]['Id'];
        $data['userId'] = $this -> userID;
        $authorise = $this -> makeRequest($authURL, $data);
        $_SESSION['token'] = $authorise['Token'];
        $this -> token = $authorise['Token'];
        $_SESSION['server'] = $authorise['Server'];
        $this -> server = $authorise['Server'];
        return true;
    }
    
    function getCategoryInfo() {
        $url = $this -> server . '/api/Inventory/GetCategories';
        $response = $this -> request($url);
        $categories = array();
        foreach ($response as $category) {
            $newCategory = array();
            $newCategory['name'] = $category['CategoryName'];
            $newCategory['id'] = $category['CategoryId'];
            $categories[] = $newCategory;
        }
        
        return $categories;
    }
    
    function getCategorynames() {
        $cateogryNames = array();
        $categoryInfo = $this -> getCategoryInfo();
        foreach ($categoryInfo as $category){
            $cateogryNames[] = $category['name'];
        }
        return $cateogryNames;
    }
    
    function getCategoryIDs() {
        $cateogryIDs = array();
        $categoryInfo = $this -> getCategoryInfo();
        foreach ($categoryInfo as $category){
            $cateogryIDs[] = $category['id'];
        }
        return $cateogryIDs;
    }
    
    function getPackagingGroupInfo() {
        $url = $this -> server . '/api/Inventory/GetPackageGroups';
        $response = $this -> request($url);
        $packagingGroups = array();
        foreach ($response as $group) {
            $newGroup = array();
            $newGroup['name'] = $group['Key'];
            $newGroup['id'] = $group['Value'];
            $packagingGroups[] = $newGroup;
        }
        
        return $packagingGroups;
    }
    
    function getShippingMethodInfo() {
        $url = $this -> server . '/api/Orders/GetShippingMethods';
        $response = $this -> request($url);
        $shippingMethods = array();
        foreach ($response as $service) {
            foreach ($service['PostalServices'] as $method){
                $newMethod = array();
                $newMethod['vendor'] = $method['Vendor'];
                $newMethod['id'] = $method['pkPostalServiceId'];
                $newMethod['trackingRequired'] = $method['TrackingNumberRequired'];
                $newMethod['name'] = $method['PostalServiceName'];
                $shippingMethods[] = $newMethod;
            }
        }
        
        return $shippingMethods;
    }
    
    function getChannels() {
        $url = $this -> server . '/api/Inventory/GetChannels';
        $response = $this -> request($url);
        $channels = array();
        foreach ($response as $channel) {
            $channels[] = $channel['Source'] . ' ' . $channel['SubSource'];
        }
        return $channels;
    }
    
    function get_location_info() {
        $url = $this -> server . '/api/Inventory/GetStockLocations';
        $response = $this -> request($url);
        $locations = array();
        foreach ($response as $location) {
            $newLocation = array();
            $newLocation['name'] = $location['LocationName'];
            $newLocation['id'] = $location['StockLocationId'];
            $locations[] = $newLocation;
        }
        return $locations;
    }
    
    function getNewSku() {
        $url = $this -> server . '/api/Stock/GetNewSKU';
        $response = $this -> request($url);
        return $response;
    }
    
    function uploadImage($data) {
        $url = $this -> server . '/api/Uploader/UploadFile?type=Image&expiredInHours=24&token=' . $this -> token;
        $curl = $this->curl;
        $headers = array(
            'Content-Type: multipart/form-data',
        );
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        //print_r($header);
        //echo "<br />";
        //echo "<br />";
        //print_r($body);
        //echo "<br />";
        //echo "<br />";
        //echo "<br />";
        //echo "<br />";
        $responseJson = json_decode($body, true);
        return $responseJson;
    }
    
    function assignImages($productGuid, $imageGuidArray) {
        $url = $this->server . '/api/Inventory/UploadImagesToInventoryItem';
        $data = array();
        $data['inventoryItemId'] = $productGuid;
        $data['imageIds'] = json_encode($imageGuidArray);
        echo "<br />";
        //print_r($data);
        echo "<br />";
        $response = $this->request($url, $data);
        return $response;
    }
    
    function setPrimaryImage($productGuid, $imageGuid) {
        $url = $this->server . '/api/Inventory/SetInventoryItemImageAsMain';
        $data = array();
        $data['inventoryItemId'] = $productGuid;
        $data['mainImageId'] = $imageGuid;
        $response = $this->request($url, $data);
        return $response;
    }
    
    function getVariationGroupIdBySKU($sku) {
        $url = $this -> server . '/api/Stock/SearchVariationGroups';
        $data = array();
        $data['searchText'] = $sku;
        $data['searchType'] = 'ParentSKU';
        $data['entriesPerPage'] = '100';
        $data['pageNumber'] = 1;
        $response = $this -> request($url, $data);
        return $response['Data'][0]['pkVariationItemId'];
    }
    
    function SKU_Exists($sku) {
        $url = $this -> server . '/api/Stock/SKUExists';
        $data = array();
        $data['SKU'] = $sku;
        $response = $this->request($url, $data);
        return $response;
    }
    
    function get_location_ids() {
        $locations = array();
        foreach ($this -> get_location_info() as $location) {
            $locations[] = $location['id'];
        }
        return $locations;
    }
    
    function get_inventory_views() {
        $url = $this -> server . '/api/Inventory/GetInventoryViews';
        $response = $this -> request($url);
        $response_json = json_decode($response);
        return $response_json;
    }
    
    function get_new_inventory_view() {
        $url = $this -> server . '/api/Inventory/GetNewInventoryView';
        $response = $this -> request($url);
        return $response;
    }
    
    function get_inventory_items($start=0, $count=1, $view=null) {
        if ($view == null) {
            $view = $this -> get_new_inventory_view();
        }
        
        $url = $this -> server . '/api/Inventory/GetInventoryItems';
        $view_json = json_encode($view);
        $locations = json_encode($this -> get_location_ids());
        $data = array();
        $data['view'] = $view_json;
        $data['stockLocationIds'] = $locations;
        $data['startIndex'] = $start;
        $data['itemsCount'] = $count;
        //echo "<br /><br />";
        //print_r($data);
        echo "<br /><br />";
        $response = $this -> request($url, $data);
        return $response;
    }
    
    function getInventoryItemIdBySKU($sku) {
        $view = $this -> get_new_inventory_view();
        //print_r($view);
        $view['Columns'] = array();
        $filter = array();
        $filter['Value'] = $sku;
        $filter['Field'] = 'String';
        $filter['FilterName'] = 'SKU';
        $filter['FilterNameExact'] = '';
        $filter['Condition'] = 'Equals';
        $view['Filters'] = [$filter];
        echo "<br /><br />";
        //print_r($view);
        $response = $this -> get_inventory_items(0, 1, $view=$view);
        //print_r($response);
        $stock_id = $response['Items'][0]['Id'];
        return $stock_id;
    }
    
    function get_inventory_item_by_id($stock_id, $inventory_item=true) {
        $url = $this -> server . '/api/Inventory/GetInventoryItemById';
        $data = array();
        $data['id'] = $stock_id;
        $response = $this -> request($url, $data);
        if ($inventory_item != true) {
            return $response;
        } else {
            $item = new InventoryItem($this, $stock_id);
            $item -> sku = $response['ItemNumber'];
            $item -> title = $response['ItemTitle'];
            $item -> purchase_price = $response['PurchasePrice'];
            $item -> retail_price = $response['RetailPrice'];
            $item -> barcode = $response['BarcodeNumber'];
            $item -> category_id = $response['CategoryId'];
            $item -> depth = $response['Depth'];
            $item -> height = $response['Height'];
            $item -> package_group_id = $response['PackageGroupId'];
            $item -> postage_service_id = $response['PostalServiceId'];
            $item -> tax_rate = $response['TaxRate'];
            $item -> variation_group_name = $response['VariationGroupName'];
            $item -> weight = $response['Weight'];
            $item -> width = $response['Width'];
            $item -> meta_data = $response['MetaData'];
            $item -> quantity = $this -> get_stock_level_by_id($stock_id);
            
            foreach ($this -> get_category_info() as $category) {
                if ($category['id'] == $item -> category_id) {
                    $item -> category = $category['name'];
                }
            }
            
            foreach ($this -> get_packaging_group_info() as $package_group) {
                if ($package_group['id'] == $item -> package_group_id) {
                    $item -> package_group = $package_group['name'];
                }
            }
            
            foreach ($this -> get_shipping_method_info() as $postage_service) {
                if ($postage_service['id'] == $item -> postage_service) {
                    $item -> postage_service = $postage_service['name'];
                }
            }
            return $item;
        }
    }
    
    function get_inventory_item_extended_properties($stock_id) {
        $url = $this -> server . '/api/Inventory/GetInventoryItemExtendedProperties';
        $data = array();
        $data['inventoryItemId'] = $stock_id;
        $response = $this -> request($url, $data);
        return $response;
    }
    
    function get_stock_level_by_id($stock_id, $location='Default') {
        $url = $this -> server . '/api/Stock/GetStockLevel';
        $data = array();
        $data['stockItemId'] = $stock_id;
        $response = $this -> request($url, $data);
        foreach ($response as $loc) {
            if ($loc['Location']['LocationName'] == $location) {
                return $loc['Available'];
            }
        }
    }
    
    function get_category_info() {
        $url = $this -> server . '/api/Inventory/GetCategories';
        $response = $this -> request($url);
        $categories = array();
        foreach ($response as  $category) {
            $new_category = array();
            $new_category['name'] = $category['CategoryName'];
            $new_category['id'] = $category['CategoryId'];
            $categories[] = $new_category;
        }
        return $categories;
    }
    
    function get_packaging_group_info() {
        $url = $this -> server . '/api/Inventory/GetPackageGroups';
        $response = $this -> request($url);
        $packaging_groups = array();
        foreach ($response as $group) {
            $new_group = array();
            $new_group['id'] = $group['Value'];
            $new_group['name'] = $group['Key'];
            $packaging_groups[] = $new_group;
        }
        return $packaging_groups;
    }
    
    function get_shipping_method_info() {
        $url = $this -> server . '/api/Orders/GetShippingMethods';
        $response = $this -> request($url);
        $shipping_methods = array();
        foreach ($response as $service) {
            foreach ($service['PostalServices'] as $method) {
                $new_method = array();
                $new_method['vendor'] = $method['Vendor'];
                $new_method['id'] = $method['pkPostalServiceId'];
                $new_method['tracking_required'] = $method['TrackingNumberRequired'];
                $new_method['name'] = $method['PostalServiceName'];
                $shipping_methods[] = $new_method;
            }
        }
        return $shipping_methods;
    }
}