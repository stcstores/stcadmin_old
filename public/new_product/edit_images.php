<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
$product = $_SESSION['new_product'];

$product = $_SESSION['new_product'];
$products = array($product);
if ($product->details['var_type']->value == true) {
    foreach ($product->variations as $variation) {
        $products[] = $variation;
    }
}

var_dump($_POST);

$guid = $_POST['guid'];
$sku = $_POST['sku'];

if (isset($_POST['remove'])) {
    foreach ($products as $currentProduct) {
        if ($currentProduct->details['sku']->text == $sku) {
            $currentProduct->removeImage($guid);
        }
    }
}

if (isset($_POST['setprime'])) {
    foreach ($products as $currentProduct) {
        if ($currentProduct->details['sku']->text == $sku) {
            $currentProduct->setImagePrimary($guid);
        }
    }
}

?>
