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