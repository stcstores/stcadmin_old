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

$fields = getVarSetupFields();
$values = getVarSetupValues();
?>

<script>
    productName = <?php echo $product->details['item_title']->text; ?>;
    var fields = <?php echo json_encode($fields); ?>;
    var values = <?php echo json_encode($values); ?>;
    keyFields = <?php echo json_encode($product->keyFields); ?>;
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script  src="/scripts/jquery.doubleScroll.js"></script>

<div class="pagebox">
    <h2>Set Variations for <?php echo $product->details['item_title']->text; ?></h2>
    <div>
        <table id="add_variation_types" class="form_section">
            
        </table>
    </div>
    <br />
    <div>
        <table id="add_variations" class="form_section">
            <col width=10% />
            <col width=40% />
            <col width=5% />
            <col width=5% />
            <col width=45% />
        </table>
    </div>
    <div>
        <table id="list_of_variations" class="form_section">
            
        </table>
    </div>
    <br />
    <div id="var_error" class="hidden" ></div>
    <form method="post" id="var_form" enctype="multipart/form-data">
        <div class="variation_table">
            <table id="var_setup" class="form_section" >
                
            </table>
        </div>
        <table class="form_nav">
            <tr>
                <td>
                    <input value="<< Previous" type="submit" name="previous" />
                    <input value="Next >>" type="submit" name="next" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script src="/scripts/var_form_validate.js"></script>
<script src="/scripts/variation_table.js"></script>

<?php
    $_SESSION['new_product'] = $product;
?>
<script>
    keyFields = <?php echo json_encode(getKeyFields()); ?>
</script>
<script>
    shippingPrice = <?php echo $product->details['shipping_price']->value; ?>
</script>
<script src=/scripts/var_setup.js ></script>
<script src=/scripts/formstyle.js ></script>
<script src=/scripts/validation.js ></script>
<script>product_title = '<?php echo $product->details['item_title']->text;?>'</script>
<script>product_price = '<?php echo $product->details['retail_price']->text;?>'</script>
<script>product_description = <?php echo json_encode($product->details['short_description']->text);?></script>

<?php

include($CONFIG['footer']);

?>