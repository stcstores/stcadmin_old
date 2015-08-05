<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();

if (isset($_POST['sku'])){
    
    if (is_array($_FILES)) {
        $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
        $i=0;
        $sku = $_POST['sku'];
        $errors = array();

        if (isset($_FILES[$sku]['tmp_name'])) {
            $data = array();
            foreach ($_FILES[$sku]['tmp_name'] as $file) {
                if(is_uploaded_file($file)) {
                    $filename = $_FILES[$sku]['name'][$i];
                    $curlFile = curl_file_create(realpath($file), 'image/jpeg', $filename);
                    $image = array('file' => $curlFile);
                    $data[] = $image;
                } else {
                    $errors[] = "not uploaded file";
                }
            $i++;
            }
        }
        if (count($errors) == 0){
            $errors[] = 'No Errors';
        }
        foreach($errors as $err) {
            //echo "<p class=error >{$err}</p>";
        }
    }
    
    $response = array();
    
    foreach ($data as $image){
        $response[] = $api -> uploadImage($image);
    }
    print_r($response);
} else {
    echo "no sku";
}

?>