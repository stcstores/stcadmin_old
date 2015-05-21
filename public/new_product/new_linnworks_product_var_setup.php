<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();
    
    
if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}

if ( !empty( $_POST ) ) {
    add_variation($product);
    
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    }
    
    if ( true ) { // error check
        $_SESSION['new_product'] = $product;
        header('Location: new_linnworks_product_ebay.php');
        exit();
    }
}

require_once($CONFIG['header']);

$product = $_SESSION['new_product'];

echo "<script src=/scripts/formstyle.js ></script>";
echo "<script src=/scripts/validation.js ></script>";

write_var_setup_page($product);


include($CONFIG['footer']);

?>