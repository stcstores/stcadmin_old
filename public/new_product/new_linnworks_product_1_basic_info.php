<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    require_once($CONFIG['check_login']);

if ( !empty($_POST) ) {
    $product = add_basic_info();
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    }
    
    if (true) { // error check
        $_SESSION['new_product'] = $product;
        
        if ($product->details['var_type']->value == true) {
            header('Location: new_linnworks_product_var_setup.php');
            exit();
        }
        
        header('Location: new_linnworks_product_2_extended_properties.php');
        exit();
    }
    
} else {
    if (isset($_SESSION['new_product'])){
        $product = $_SESSION['new_product'];
    } else {
        $product = new NewProduct();
    }
}

require_once($CONFIG['header']);

writeFormPage('basic', $product);

$_SESSION['new_product'] = $product;

echo "<script src=/scripts/forms.js ></script>";

include($CONFIG['footer']);

?>