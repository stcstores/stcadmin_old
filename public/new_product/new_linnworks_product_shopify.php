<?php

    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();
    
    
    $product = $_SESSION['new_product'];
    if ( !empty($_POST) ) {
        if (isset($_POST['previous'])) {
            header('Location: new_linnworks_product_amazon.php');
            exit();
        }
        add_chn_shopify($product);
        
        if (true) { // error check
            $_SESSION['new_product'] = $product;
            header('Location: imageupload.php');
            exit();
        }
    }
    
    
    
require_once($CONFIG['header']);
    
    writeFormPage('chn_shopify', $product);
    
    $_SESSION['new_product'] = $product;
    
    echo "<script src=/scripts/forms.js ></script>";

include($CONFIG['footer']);

?>