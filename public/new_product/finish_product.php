<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);


echo "<div id=testproduct class=pagebox>";
if (!isset($_SESSION['new_product'])) {
    echo "No Object";
} else {
    $product = $_SESSION['new_product'];
    echo "<input type=button value='Write Product' id=write_product />";
    ?>
    <h3>Basic Product Info</h3>
    <table>
        <tr>
            <td>Product SKU</td>
            <td><input value="<?php echo $product->details['sku']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>Product Title</td>
            <td><input value="<?php echo $product->details['item_title']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea rows=8 cols=75 disabled ><?php echo $product->details['short_description']->text; ?></textarea></td>
        </tr>
        <tr>
            <td>Department</td>
            <td><input value="<?php echo $product->details['department']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>Brand</td>
            <td><input value="<?php echo $product->details['brand']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>Manufacturer</td>
            <td><input value="<?php echo $product->details['manufacturer']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>Shipping Method</td>
            <td><input value="<?php echo $product->details['shipping_method']->text; ?>" disabled /></td>
        </tr>
    </table>
    
    <?php
            if (count($product->variations) > 0) {
                echo "<h3>Variations</h3>";
                echo "<table id=testvar>";
                echo "<tr>";
                $fields = getVarSetupFields();
                
                $ignoreFields = ['var_append'];
                
                foreach ($fields as $field) {
                    if (!(in_array($field['field_name'], $ignoreFields))) {
                        echo "<th>";
                        echo $field['field_title'];
                        echo "</th>";
                    }
                }
                echo "<th>Images</th>";
                foreach ($product->variations as $variation) {
                    echo "<tr>";
                    foreach ($fields as $field) {
                        if (!(in_array($field['field_name'], $ignoreFields))) {
                            echo "<td>";
                            echo '<input value="' . $variation->details[$field['field_name']]->text . '" disabled size=10/>';
                            echo "</td>";
                        }
                    }
                    echo "<td class=image_row>";
                    foreach (getImageIdsForSKU($variation->details['sku']->text) as $image) {
                        echo "<img class=in_table_image src='image.php?id=" . $image['id'] . "' width=50 />";
                    }                    
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<h3>Extended Properties</h3>";
                echo "<table>";
                $fields = getFormFieldsByPage('extended_properties');
                foreach ($fields as $field) {
                    echo "<tr>";
                    echo "<td>";
                    echo $field['field_title'];
                    echo "</td>";
                    echo "<td>";
                    echo '<input value="' . $product->details[$field['field_name']]->text . '" disabled />';
                    echo "</td>";
                    echo "</tr>";
                }
                echo "<tr>";
                echo "<td>Images</td>";
                echo "<td class=image_row>";
                foreach (getImageIdsForSKU($product->details['sku']->text) as $image) {
                    echo "<img class=in_table_image src='image.php?id=" . $image['id'] . "' width=100 />";
                }                    
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }
        ?>
    
    <h3>eBay Details</h3>
    <table>
        <tr>
            <td>eBay Title</td>
            <td><input value="<?php echo $product->details['ebay_price']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>eBay Price</td>
            <td><input value="<?php echo $product->details['ebay_price']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>eBay Description</td>
            <td><textarea rows=8 cols=75 disabled ><?php echo $product->details['ebay_description']->text; ?></textarea></td>
        </tr>
    </table>
    
    <h3>Amazon Details</h3>
    <table>
        <tr>
            <td>Amazon Title</td>
            <td><input value="<?php echo $product->details['am_price']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>Amazon Price</td>
            <td><input value="<?php echo $product->details['am_price']->text; ?>" disabled /></td>
        </tr>
        <?php
        for ($i=1; $i<6; $i++) {
            echo "<tr>";
            echo "<td>Amazon Bullet " . $i . "</td>";
            echo '<td><input value="' . $product->details['am_bullet_' . $i]->text . '" disabled /></td>';
            echo "</tr>";
        }
        ?>
        <tr>
            <td>Amazon Description</td>
            <td><textarea rows=8 cols=75 disabled ><?php echo $product->details['am_description']->text; ?></textarea></td>
        </tr>
    </table>
    
    <h3>stcstores.co.uk Details</h3>
    <table>
        <tr>
            <td>stcstores.co.uk Title</td>
            <td><input value="<?php echo $product->details['shopify_title']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>stcstores.co.uk Price</td>
            <td><input value="<?php echo $product->details['shopify_price']->text; ?>" disabled /></td>
        </tr>
        <tr>
            <td>stcstores.co.uk Description</td>
            <td><textarea rows=8 cols=75 disabled ><?php echo $product->details['shopify_description']->text; ?></textarea></td>
        </tr>
    </table>
    
    <script>
        $('#write_product').click(function() {
            $.ajax({
                url: 'writeproduct.php',
                async: false,
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
        
    </script>
    
    <?php
}

require_once($CONFIG['footer']);