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
$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);
$database = new STCAdmin\Database();
$item_title = $product->getLinnTitle();

echo "<input type=button value='Create Product' id=create_product ";
if ($product->onServer()) {
    echo ' disabled ';
}
echo "/>\n";
?>
    <h3>Basic Product Info <a class="editlink" href="new_linnworks_product_1_basic_info.php" >Edit</a></h3>
    <table>
        <tr>
            <td>Product ID</td>
            <?php
            $guid = $product->details['guid']->text;
            $size = strlen($product->details['guid']->text) + 2;
            ?>
            <td><input value="<?php echo $guid ?>" size="<?php echo $size ?>" class=disabled readonly size=38 /></td>
        </tr>
        <tr>
            <td>Product SKU</td>
            <?php
            $sku = $product->details['sku']->text;
            $size = strlen($product->details['sku']->text) + 2;
            ?>
            <td><input value="<?php echo $sku; ?>" size="<?php echo $size; ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Product Title</td>
            <?php
            $size = strlen($item_title);
            ?>
            <td><input value="<?php echo $item_title; ?>" class=disabled size="<?php echo $size; ?>" readonly /></td>
        </tr>
        <tr>
            <td>Description</td>
            <?php
            $description = $product->details['short_description']->text;
            ?>
            <td><textarea rows=8 cols=75 class=disabled readonly ><?php echo $description; ?></textarea></td>
        </tr>
        <tr>
            <td>Department</td>
            <?php
            $department = $product->details['department']->text;
            $size = strlen($product->details['department']->text) + 2;
            ?>
            <td><input value="<?php echo $department; ?>" size="<?php echo $size; ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Brand</td>
            <?php
            $brand = $product->details['brand']->text;
            $size = strlen($product->details['brand']->text) + 2;
            ?>
            <td><input value="<?php echo $brand; ?>" size="<?php echo $size ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Manufacturer</td>
            <?php
            $manu = $product->details['manufacturer']->text;
            $size = strlen($product->details['manufacturer']->text) + 2;
            ?>
            <td><input value="<?php echo $manu; ?>" size="<?php echo $size; ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>Shipping Method</td>
            <?php
            $method = $product->details['shipping_method']->text;
            $size = strlen($product->details['shipping_method']->text) + 2;
            ?>
            <td><input value="<?php echo $method; ?>" size="<?php echo $size; ?>" class=disabled readonly /></td>
        </tr>
    </table>

<?php
if (count($product->variations) > 0) {
    echo '<h3>Variations<a class="editlink" href="new_linnworks_product_var_table.php" >Edit</a></h3>';
    echo "<div class=variation_table >";
    echo "<table id=testvar >";
    echo "<tr>";
    $fields = $database->getVarSetupFields();

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
        $var_names[] = $variation->getLinnTitle();
    }
    $max_var_name = max(array_map('strlen', $var_names));

    foreach ($product->variations as $variation) {
        echo "<tr>";
        echo "<td><input value='" . $variation->details['sku']->text . "' class=disabled readonly size=11 /></td>";
        foreach ($fields as $field) {
            if (!(in_array($field['field_name'], $ignoreFields))) {
                $var_values = array();
                foreach ($product->variations as $_variation) {
                    $var_values[] = $_variation->details[$field['field_name']]->text;
                }
                $field_size = max(array_map('strlen', $var_values));
                if ($field_size < 5) {
                    $field_size = 5;
                }
                echo "<td>";
                echo '<input value="';
                if (in_array($field['field_name'], array('shipping_price', 'retail_price', 'purchase_price'))) {
                    echo '&pound;' . sprintf("%0.2f", $variation->details[$field['field_name']]->text) .'" size=5';
                } else if ($field['field_name'] == 'var_name') {
                    echo $variation->getLinnTitle() . '" size="' . $max_var_name . ' "';
                } else {
                    echo $variation->details[$field['field_name']]->text .'" size=' . $field_size;
                }
                echo " class=disabled readonly /></td>\n";
            }
        }
        echo "<td class=image_row>";
        foreach ($variation->images as $image) {
            echo "<img class=in_table_image src='" . $image->thumbPath . "' />";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo '<h3>Extended Properties<a class="editlink" ';
    echo 'href="new_linnworks_product_2_extended_properties.php" >Edit</a></h3>';
    echo "<table>";
    $fields = $database->getFormFieldsByPage('extended_properties');
    foreach ($fields as $field) {
        echo "<tr>";
        echo "<td>";
        echo $field['field_title'];
        echo "</td>";
        echo "<td>";
        echo '<input value="';
        if (in_array($field['field_name'], array('shipping_price', 'retail_price', 'purchase_price'))) {
            echo '&pound;' . sprintf("%0.2f", $product->details[$field['field_name']]->text) .'" size="5';
        } else {
            echo $product->details[$field['field_name']]->text;
            echo '" size=' . strlen($product->details[$field['field_name']]->text);
        }
        echo '" class=disabled readonly />';
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
if (count($product->images > 0)) {
    echo "<h3>Images</h3>\n";
    echo "<table>\n";
    echo "<tr>";
    foreach ($product->images as $image) {
        echo "<td class=image_row><img class=in_table_image src='" . $image->thumbPath . "' /></td>";
    }
    echo "</tr>\n";
    echo "</table>\n";
}
?>
    <h3>eBay Details<a class="editlink" href="new_linnworks_product_ebay.php" >Edit</a></h3>
    <table>
        <tr>
            <td>eBay Title</td>
            <td><input value="<?php echo $product->details['ebay_title']->text; ?>" size="<?php echo strlen($product->details['ebay_title']->text); ?>" class=disabled readonly /></td>
        </tr>
        <tr>
            <td>eBay Description</td>
            <td><div class=description ><?php echo $product->toHTML($product->details['short_description']->text); ?></div></td>
            <td><textarea rows=15 cols=50 class=disabled readonly ><?php echo $product->toHTML($product->details['short_description']->text); ?></textarea></td></td>
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
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f", $product->details['shipping_de']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Europe</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f", $product->details['shipping_eu']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>United States</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f", $product->details['shipping_usa']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Australia</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f", $product->details['shipping_aus']->value);?>' size=5 /></td>
            </tr>
            <tr>
                <td>Rest of World</td>
                <td><input class=disabled readonly value='&pound;<?php echo sprintf("%0.2f", $product->details['shipping_row']->value);?>' size=5 /></td>
            </tr>
        </table>
    <?php
} else {
    echo "<div class=variation_table>\n";
    echo "<table id=international_shipping_table>\n";
    foreach ($product->keyFields as $field => $isKey) {
        if ($isKey) {
            echo "<tr>\n";
            foreach ($product->variations as $variation) {
                echo "<th colspan=2 style='text-align: center;'>\n";
                echo $variation->details[$field]->text;
                echo "</th>\n";
            }
            echo "</tr>\n";
        }
    }
    foreach ([['France', 'fr'], ['Germany', 'de'], ['Europe', 'eu'], ['United States', 'usa'], ['Australia', 'aus'],['Rest of World', 'row']] as $country) {
        echo "<tr>\n";
        foreach ($product->variations as $variation) {
            echo "<td>" . $country[0] . "</td><td><input class=disabled readonly value='&pound;";
            echo sprintf("%0.2f", $variation->details['shipping_' . $country[1]]->value);
            echo "' size=5 /></td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "</div>\n";
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
                async: true,
                dataType: 'json',
                success: function(data){
                }
            });
            $.ajax({
                url: 'archive_new_product_csv.php',
                async: true,
                dataType: 'json',
                success: function(data){

                }
            })
            $.ajax({
                url: 'create_product.php',
                async: true,
                dataType: 'json',
                success: function(data){
                }
            });
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

        $( document ).ajaxComplete(function( event, xhr, settings ) {
            if ( settings.url === "create_product.php" ) {
              location.reload();
            }
          });


    </script>
    <script src=/scripts/formstyle.js ></script>

    <?php

require_once($CONFIG['footer']);
