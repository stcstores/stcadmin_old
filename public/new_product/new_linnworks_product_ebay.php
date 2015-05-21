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
?>

<script src=/scripts/formstyle.js ></script>
<script src=/scripts/validation.js ></script>
<script>product_title = '<?php echo $product->details['item_title']->text;?>'</script>
<script>product_price = '<?php echo $product->details['retail_price']->text;?>'</script>
<script>product_description = <?php echo json_encode($product->details['short_description']->text);?></script>
<script src=/scripts/channel_forms.js ></script>

<?php
include($CONFIG['footer']);

?>