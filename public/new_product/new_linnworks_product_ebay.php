<?php

    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();

$product = $_SESSION['new_product'];

if ( !empty($_POST) ) {
    if (isset($_POST['previous'])) {
        
        if ($product->details['var_type']->value == true) {
            header('Location: new_linnworks_product_var_setup.php');
            exit();
            
        } elseif ($product->details['var_type']->value == false) {
            header('Location: new_linnworks_product_2_extended_properties.php');
            exit();                
        }
    }
    add_chn_ebay($product);
    
    if (true) { // error check
        $_SESSION['new_product'] = $product;
        header('Location: new_linnworks_product_amazon.php');
        exit();
    }
}

require_once($CONFIG['header']);

writeFormPage('chn_ebay', $product);

$_SESSION['new_product'] = $product;

echo "<script src=/scripts/forms.js ></script>";

include($CONFIG['footer']);

?>