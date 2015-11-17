<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();

print_r($_FILES);

$product = $_SESSION['new_product'];
$products = array($product);
if ($product->details['var_type']->value == true) {
    foreach ($product->variations as $variation) {
        $products[] = $variation;
    }
}

if (isset($_POST['sku'])) {
    if (is_array($_FILES)) {
        $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
        $i=0;
        $sku = $_POST['sku'];
        $errors = array();
        foreach ($products as $item) {
            if ($item->details['sku']->text == $sku) {
                $currentProduct = $item;
            }
        }


        if (isset($_FILES[$sku]['tmp_name'])) {
            $data = array();
            foreach ($_FILES[$sku]['tmp_name'] as $file) {
                if (is_uploaded_file($file)) {
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
        if (count($errors) == 0) {
            $errors[] = 'No Errors';
        }
        foreach ($errors as $err) {
            //echo "<p class=error >{$err}</p>";
        }
    }

    $response = array();

    foreach ($data as $image) {
        $response = $api -> upload_image($image);
        $guid = $response[0]['FileId'];
        $thumbPath = $response[0]['ThumbnailUrl'];
        $fullPath = $response[0]['ImageUrl'];
        $currentProduct->images->addImage($guid, $thumbPath, $fullPath);
    }
    print_r($response);
} else if (isset($_POST['field'])) {
    if (is_array($_FILES)) {
        $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
        $i=0;
        $field = $_POST['field'];
        $value = $_POST['value'];
        $errors = array();
        if (isset($_FILES['var_type']['tmp_name'])) {
            $data = array();
            foreach ($_FILES['var_type']['tmp_name'] as $file) {
                if (is_uploaded_file($file)) {
                    $filename = $_FILES['var_type']['name'][$i];
                    $curlFile = curl_file_create(realpath($file), 'image/jpeg', $filename);
                    $image = array('file' => $curlFile);
                    $data[] = $image;
                } else {
                    $errors[] = "not uploaded file";
                }
                $i++;
            }
            $response = array();
            foreach ($data as $image) {
                foreach ($product->variations as $variation) {
                    if ($variation->details[$field]->text == $value) {
                        $response = array();
                        $response = $api -> upload_image($image);
                        $guid = $response[0]['FileId'];
                        $thumbPath = $response[0]['ThumbnailUrl'];
                        $fullPath = $response[0]['ImageUrl'];
                        $variation->images->addImage($guid, $thumbPath, $fullPath);
                    }
                }
            }
            print_r($response);
        }
    }
} else {
    echo "no sku";
}

?>
