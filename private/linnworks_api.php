<?php

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
        $datastring = http_build_query($data);
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
        $response = $this -> response($url);
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
        print_r($data);
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
}