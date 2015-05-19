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

echo "<script src=/scripts/formstyle.js ></script>";
echo "<script src=/scripts/validation.js ></script>";

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