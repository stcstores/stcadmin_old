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

?>

<div id=content>
    <form method='post' enctype='multipart/form-data'>
        <table id='basic_info' class=form_section>
            <col span='1' style='width: 10%;'>
            <col span='1' style='width: 24%;'>
            <col span='1' style='width: 33%;'>
            <col span='1' style='width: 33%;'>
            <tr>
                <td class=form_table_field_name >
                    <label for="am_title">Amazon Title</label>
                </td>
                <td class=form_table_input>
                    <input id=am_title name=am_title type=text size=50 value='<?php echo $product->details['am_title']->text; ?>' />
                </td>
                <td class=form_field_table_description >
                    Title for listing on Amazon. Must not contain special characters. Up to 80 characters.
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
                <td><input id='set_shopify_title' type=button value='Set to Shopify Title' /></td>
                <td></td>
            </tr>
            <?php
            for ($i=1; $i < 6; $i++) {
                ?>
                <tr>
                    <td class=form_table_field_name>
                        <label for="am_bullet_<?php echo $i;?>">Amazon Bullet Point <?php echo $i;?></label>
                    </td>
                    <td class=form_table_input>
                        <input id="am_bullet_<?php echo $i;?>" name="am_bullet_<?php echo $i;?>" type=text size=50 value='<?php echo $product->details['am_bullet_' . $i]->text; ?>' />
                    </td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td class=form_table_field_name >
                    <label for="am_description">Amazon Description</label>
                </td>
                <td class=form_table_input>
                    <textarea rows=4 cols=45 id=am_description name=am_description ><?php echo $product->details['am_description']->text; ?></textarea>
                </td>
                <td class=form_field_table_description >Description for Amazon.</td>
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
                <td><input id='set_shopify_description' type=button value='Set to Shopify Description' /></td>
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
       $('#am_title').val(product_title); 
    });
    
    $('#set_ebay_title').click(function() {
       $('#am_title').val(ebay_title); 
    });
    
    $('#set_shopify_title').click(function() {
       $('#am_title').val(shopify_title); 
    });
    
    
    $('#set_item_description').click(function() {
       $('#am_description').val(product_description); 
    });
    
    $('#set_ebay_description').click(function() {
       $('#am_description').val(ebay_description); 
    });
    
    $('#set_shopify_description').click(function() {
       $('#am_description').val(shopify_description); 
    });
</script>

<?php
include($CONFIG['footer']);

?>