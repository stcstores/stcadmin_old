<?php

    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();

$product = $_SESSION['new_product'];

if ( !empty($_POST) ) {
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_ebay.php');
        exit();
    }
    add_chn_amazon($product);
    
    if (true) { // error check
        $_SESSION['new_product'] = $product;
        header('Location: new_linnworks_product_shopify.php');
        exit();
    }
}

require_once($CONFIG['header']);

writeFormPage('chn_amazon', $product);

$_SESSION['new_product'] = $product;

echo "<script src=/scripts/forms.js ></script>";

include($CONFIG['footer']);

?>