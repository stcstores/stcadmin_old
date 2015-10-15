<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);


echo "<div id=testproduct class=pagebox>";
if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}
    $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
    $product_exists = $api->SKU_Exists($product->details['sku']->text);
    
    $item_title = get_linn_title($product);
    
    ?>
    
    <input type=button value='Create Product' id=create_product <?php if ($product_exists) { echo ' disabled '; } ?>/>
    
    <h3>Basic Product Info <a class="editlink" href="new_linnworks_product_1_basic_info.php" >Edit</a></h3>
    <table>
        <tr>
            <td>Product ID</td>
            <td><input value="<?php echo $product->details['guid']->text; ?>" size="<?php echo strlen($product->details['guid']->text) + 2; ?>" class=disabled readonly size=38 /></td>
        </tr>
        <tr>
            <td>Product SKU</td>
            <td><input value="<?php echo $product->details['sku']->text; ?>" size="<?php echo strlen($product->details['sku']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Product Title</td>
            <td><input value="<?php echo $item_title; ?>" class=disabled size="<?php echo strlen($item_title); ?>" readonly /></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea rows=8 cols=75 class=disabled readonly ><?php echo $product->details['short_description']->text; ?></textarea></td>
        </tr>
        <tr>
            <td>Department</td>
            <td><input value="<?php echo $product->details['department']->text; ?>" size="<?php echo strlen($product->details['department']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Brand</td>
            <td><input value="<?php echo $product->details['brand']->text; ?>" size="<?php echo strlen($product->details['brand']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Manufacturer</td>
            <td><input value="<?php echo $product->details['manufacturer']->text; ?>" size="<?php echo strlen($product->details['manufacturer']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Shipping Method</td>
            <td><input value="<?php echo $product->details['shipping_method']->text; ?>" size="<?php echo strlen($product->details['shipping_method']->text); ?>" class=disabled readonly /></td>
        </tr>
    </table>
    
    <?php
            if (count($product->variations) > 0) {
                echo '<h3>Variations<a class="editlink" href="new_linnworks_product_var_setup.php" >Edit</a></h3>';
                echo "<div class=variation_table >";
                echo "<table id=testvar >";
                echo "<tr>";
                $fields = getVarSetupFields();
                
                $ignoreFields = ['var_append'];
                echo "<th>SKU</th>";
                foreach ($fields as $field) {
                    if (!(in_array($field['field_name'], $ignoreFields))) {
                        echo "<th>";
                        echo $field['field_title'];
                        echo "</th>";
                    }
                }
                echo "<th>Images</th>";
                
                $var_names = array();
                foreach ($product->variations as $variation) {
                    $var_names[] = get_linn_title($variation);
                }
                $max_var_name = max(array_map('strlen', $var_names));
                
                foreach ($product->variations as $variation) {
                    echo "<tr>";
                    echo "<td><input value='" . $variation->details['sku']->text . "' class=disabled readonly size=11 /></td>";
                    foreach ($fields as $field) {
                        if (!(in_array($field['field_name'], $ignoreFields))) {
                            $var_values = array();
                            foreach ($product->variations as $variation) {
                                $var_values[] = $variation->details[$field['field_name']]->text;
                            }
                            $field_size = max(array_map('strlen', $var_values));
                            if ($field_size < 5) {
                                $field_size = 5;
                            }
                            echo "<td>";
                            echo '<input value="';
                            if (in_array($field['field_name'], array('shipping_price', 'retail_price', 'purchase_price'))) {
                                echo '&pound;' . sprintf("%0.2f",$variation->details[$field['field_name']]->text) .'" size=5';
                            } else if ($field['field_name'] == 'var_name') {
                                echo get_linn_title($variation) . '" size="' . $max_var_name . ' "';
                            } else {
                                echo $variation->details[$field['field_name']]->text .'" size=' . $field_size;
                            }
                            echo " class=disabled readonly /></td>\n";
                        }
                    }
                    echo "<td class=image_row>";
                    foreach ($variation->images->images as $image) {
                        echo "<img class=in_table_image src='" . $image->thumbPath . "' />";
                    }                    
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo '<h3>Extended Properties<a class="editlink" href="new_linnworks_product_2_extended_properties.php" >Edit</a></h3>';
                echo "<table>";
                $fields = getFormFieldsByPage('extended_properties');
                foreach ($fields as $field) {
                    echo "<tr>";
                    echo "<td>";
                    echo $field['field_title'];
                    echo "</td>";
                    echo "<td>";
                    echo '<input value="';
                    if (in_array($field['field_name'], array('shipping_price', 'retail_price', 'purchase_price'))) {
                        echo '&pound;' . sprintf("%0.2f",$product->details[$field['field_name']]->text) .'" size="5';
                    } else {
                        echo $product->details[$field['field_name']]->text .'" size=' . strlen($product->details[$field['field_name']]->text);
                    }
                    echo '" class=disabled readonly />';
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
    <h3>Images</h3>
    <table>
        <tr>
            <?php
                foreach ($product->images->images as $image) {
                    echo "<td class=image_row><img class=in_table_image src='" . $image->thumbPath . "' /></td>";
                }
            ?>
        </tr>
    </table>
                
    <h3>eBay Details<a class="editlink" href="new_linnworks_product_ebay.php" >Edit</a></h3>
    <table>
        <tr>
            <td>eBay Title</td>
            <td><input value="<?php echo $product->details['ebay_title']->text; ?>" size="<?php echo strlen($product->details['ebay_title']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>eBay Description</td>
            <td><div class=description ><?php echo to_html($product->details['ebay_description']->text); ?></div></td>
            <td><textarea rows=15 cols=50 class=disabled readonly ><?php echo to_html($product->details['ebay_description']->text); ?></textarea></td></td>
        </tr>
    </table>
    
    <h3>Amazon Details<a class="editlink" href="new_linnworks_product_amazon.php" >Edit</a></h3>
    <table>
        <tr>
            <td>Amazon Title</td>
            <td><input value="<?php echo $product->details['am_title']->text; ?>" size="<?php echo strlen($product->details['am_title']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Amazon Description</td>
            <td>
                <div class=description >
                    <ul>
                        <?php
                            $i = 1;
                            while ($i < 6) {
                                echo "<li>" . $product->details['am_bullet_' . $i]->text . "</li>";
                                $i ++;
                            }
                            
                        ?>
                    </ul>
                    <?php echo to_html($product->details['am_description']->text); ?>
                </div>
            </td>
            <td><td><textarea rows=15 cols=50 class=disabled readonly ><?php echo to_html($product->details['am_description']->text); ?></textarea></td></td>
        </tr>
    </table>
    
    <h3>stcstores.co.uk Details<a class="editlink" href="new_linnworks_product_shopify.php" >Edit</a></h3>
    <table>
        <tr>
            <td>stcstores.co.uk Title</td>
            <td><input value="<?php echo $product->details['shopify_title']->text; ?>" size="<?php echo strlen($product->details['shopify_title']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>stcstores.co.uk Description</td>
            <td><div class=description ><?php echo to_html($product->details['shopify_description']->text); ?></div></td>
            <td><textarea rows=15 cols=50 class=disabled readonly ><?php echo to_html($product->details['shopify_description']->text); ?></textarea></td>
        </tr>
    </table>
    <?php if (count($product->variations) == 0) { ?>
    <h3>International Shipping</h3>
        <table>
            <tr>
                <td>France</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$product->details['shipping_fr']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Germany</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$product->details['shipping_de']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Europe</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$product->details['shipping_eu']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>United States</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$product->details['shipping_usa']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Australia</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$product->details['shipping_aus']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Rest of World</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$product->details['shipping_row']->value);?>' size=5 /></td>
            </tr>
        </table>
    <?php
    } else {
        ?>
        <div class=variation_table>
            <table>
            <tr>
            <?php
            foreach ($product->variations as $variation) {
                ?><td colspan=2 /><?php echo $variation->details['var_name']->text;?></td><?php            
            }
            ?>
                </tr>
                <?php
                foreach ([['France', 'fr'], ['Germany', 'de'], ['Europe', 'eu'], ['United States', 'usa'], ['Australia', 'aus'],['Rest of World', 'row']] as $country){
                ?>
                    <tr>
                        <?php foreach($product->variations as $variation) {
                            ?>
                                <td><?php echo $country[0]; ?></td><td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f",$variation->details['shipping_' . $country[1]]->value);?>' size=5 /></td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php
    }
    ?>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
    <script  src="/scripts/jquery.doubleScroll.js"></script>
    <script>
        $('#create_product').click(function() {
            $('#content').empty()
            $('#content').html('<div style="margin 0 auto;" ><img class=working src=/images/ajax-loader.gif alt=working /></div>');
            $.ajax({
                url: 'writeproduct.php',
                async: false,
                dataType: 'json',
                success: function(data){
                }
            });
            $.ajax({
                url: 'archive_new_product_csv.php',
                async: false,
                dataType: 'json',
                success: function(data){
                    
                }
            })
            $.ajax({
                url: 'create_product.php',
                async: false,
                dataType: 'json',
                success: function(data){
                }
            });
            location.reload();
        });
        
        $(':input').each(function() {
            if ($(this).prop('disabled')) {
                var valLength = $(this).val().length
                if (valLength > 5) {
                    $(this).attr('size', valLength + 2);
                } else {
                    $(this).attr('size', 5);
                }
            }
        });
        $(document).ready(function(){
            $('.variation_table').doubleScroll();
        });
    
        
    </script>
    
    <?php


require_once($CONFIG['footer']);