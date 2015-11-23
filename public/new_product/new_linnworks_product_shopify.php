<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();


if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}

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

?>

<div class=small_form_container>
    <form method='post' enctype='multipart/form-data'>
        <table id='basic_info' class=form_section>
            <col span='1' style='width: 10%;'>
            <col span='1' style='width: 24%;'>
            <col span='1' style='width: 33%;'>
            <col span='1' style='width: 33%;'>
            <tr>
                <td class=form_table_field_name >
                    <label for="shopify_title">Shopify Title</label>
                </td>
                <td class=form_table_input>
                    <input id=shopify_title name=shopify_title type=text size=50 value='<?php echo $product->details['shopify_title']->text; ?>' />
                </td>
                <td class=form_field_table_description >
                    Title for listing on Shopify. Must not contain <a href="/new_product/specialcharacters.php" tabindex=-1>special characters</a>.
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input id='set_item_title' type=button value='Set to Item Title' /></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><input id='set_ebay_title' type=button value='Set to eBay Title' /></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><input id='set_amazon_title' type=button value='Set to Amazon Title' /></td>
                <td></td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="shopify_description">Shopify Description</label>
                </td>
                <td class=form_table_input>
                    <textarea rows=4 cols=45 id=shopify_description name=shopify_description ><?php echo $product->details['shopify_description']->text; ?></textarea>
                </td>
                <td class=form_field_table_description >Description for Shopify.</td>
            </tr>
            <tr>
                <td></td>
                <td><input id='set_item_description' type=button value='Set to Item Description' /></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><input id='set_ebay_description' type=button value='Set to eBay Description' /></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><input id='set_amazon_description' type=button value='Set to Amazon Description' /></td>
                <td></td>
            </tr>
        </table>
        <table class=form_nav>
            <tr>
                <td>
                    <input value='<< Previous' type=submit name=previous />
                    <input value='Next >>' type=submit name=next />
                </td>
            </tr>
        </table>
    </form>
</div>


<?php
$_SESSION['new_product'] = $product;
?>

<script src=/scripts/formstyle.js ></script>
<script src=/scripts/validation.js ></script>
<script>product_title = '<?php echo $product->details['item_title']->text;?>'</script>
<script>product_price = '<?php echo $product->details['retail_price']->text;?>'</script>
<script>product_description = <?php echo json_encode($product->details['short_description']->text);?></script>
<script>ebay_title = '<?php echo $product->details['ebay_title']->text;?>'</script>
<script>ebay_price = '<?php echo $product->details['ebay_price']->text;?>'</script>
<script>ebay_description = <?php echo json_encode($product->details['ebay_description']->text);?></script>
<script>amazon_title = '<?php echo $product->details['am_title']->text;?>'</script>
<script>amazon_price = '<?php echo $product->details['am_price']->text;?>'</script>
<script>amazon_description = <?php echo json_encode($product->details['am_description']->text);?></script>
<script>shopify_title = '<?php echo $product->details['shopify_title']->text;?>'</script>
<script>shopify_price = '<?php echo $product->details['shopify_price']->text;?>'</script>
<script>shopify_description = <?php echo json_encode($product->details['shopify_description']->text);?></script>

<script>
    $('#set_item_title').click(function() {
       $('#shopify_title').val(product_title);
    });

    $('#set_ebay_title').click(function() {
       $('#shopify_title').val(ebay_title);
    });

    $('#set_amazon_title').click(function() {
       $('#shopify_title').val(amazon_title);
    });


    $('#set_item_description').click(function() {
       $('#shopify_description').val(product_description);
    });

    $('#set_ebay_description').click(function() {
       $('#shopify_description').val(ebay_description);
    });

    $('#set_amazon_description').click(function() {
       $('#shopify_description').val(amazon_description);
    });
</script>

<?php

include($CONFIG['footer']);

?>
