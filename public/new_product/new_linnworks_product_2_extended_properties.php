<?php

    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    require_once($CONFIG['check_login']);

$product = $_SESSION['new_product'];

if ( !empty($_POST) ) {
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    }
    add_extended_properties($product);
    
    if (true) { // error check
        $_SESSION['new_product'] = $product;
        header('Location: new_linnworks_product_ebay.php');
        exit();
    }
}    

require_once($CONFIG['header']);

writeFormPage('extended_properties', $product);

$_SESSION['new_product'] = $product;

echo "<script src=/scripts/forms.js ></script>";

include($CONFIG['footer']);

?>