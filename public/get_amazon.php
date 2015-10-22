<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

function extended_property_value($item, $property) {
    $ex_prop = $item -> extended_properties -> get_property_by_name($property);
    if ($ex_prop != null) {
        return $ex_prop -> value;
    } else {
        return '';
    }
}

?>

<div class=pagebox >
    <form method=post >
        <label for='sku' >SKU: </label>
        <input name='sku' value='<?php if (isset($_POST['sku'])) { echo $_POST['sku']; } ?>' />
        <input type=submit value='Get Info' />
    </form>
    
    <?php
    
    if (isset($_POST['sku'])) {
        
        $sku = $_POST['sku'];
        
        $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
        
        $guid = $api -> get_inventory_item_id_by_SKU($sku);
        
        if ($guid == null) {
            $guid = $api -> get_variation_group_id_by_SKU($sku);
            $has_variations = true;
            if ($guid == null) {
                echo "<p class='error'>Product Not Found</p>";
            }
        } else {
            $has_variations = false;
        }
        
        if ($guid == null) {
            echo "<p class='error'>Product Not Found</p>";
        } else {
            $item = $api -> get_inventory_item_by_id($guid);
            
            if ($has_variations == false) {
            ?>
            <table class='item_details'>
                <tr>
                    <td>Find Product by Barcode: </td>
                    <td><?php echo $item -> barcode; ?></td>
                </tr>
            </table>
            <?php
            
            } else {
                $variations = array();
                foreach ($api -> get_variation_children($item -> stock_id) as $guid) {
                    $variations[] = $api -> get_inventory_item_by_id($guid);
                }
                ?>
            <p>Find Product by Barcode: </p>
            <ul class='item_details'>
                <?php foreach($variations as $variation) { ?>
                    <li><?php echo $variation -> barcode; ?></li>
                <?php } ?>
            </ul>
            <?php
            }
            ?>
    
            <h2>Vital Info</h2>
            <table class='item_details'>
                <tr>
                    <td class='align_right'>Title</td>
                    <td><input value='<?php
                    $title = $api -> get_channel_titles($item -> stock_id)['amazon'];
                    echo $title; ?>' size='<?php echo strlen($title); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Manufacturer</td>
                    <td><input value='<?php echo extended_property_value($item, 'Manufacturer'); ?>' size='<?php echo strlen(extended_property_value($item, 'Manufacturer')); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Brand</td>
                    <td><input value='<?php echo extended_property_value($item, 'Brand'); ?>' size='<?php echo strlen(extended_property_value($item, 'Brand')); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Manufacturer Part Number</td>
                    <td><input value='<?php echo extended_property_value($item, 'MPN'); ?>' size='<?php echo strlen(extended_property_value($item, 'MPN')); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Material</td>
                    <td><input value='<?php echo extended_property_value($item, 'Material'); ?>' size='<?php echo strlen(extended_property_value($item, 'Material')); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Size</td>
                    <td><input value='<?php echo extended_property_value($item, 'Size'); ?>' size='<?php echo strlen(extended_property_value($item, 'Size')); ?>' readonly /></td>
                </tr>
                <tr>
                    <?php
                    if ($has_variations == true) {
                        ?>
                        <td class='align_right'>Variation Theme</td>
                        <td><input value='<?php
                        
                        $string = '';
                        
                        $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
                        $variation_types = array();
                        foreach ($ex_props as $prop) {
                            if (extended_property_value($variations[0], $prop) != '') {
                                if (strlen(extended_property_value($variations[0], $prop)) > 0) {
                                    $var_type = str_replace('var_', '', $prop);
                                    $variation_types[] = $var_type;
                                    $string = $string . $var_type . ' ';
                                }
                            }
                        }
                        if (strlen(trim($string)) > 0) {
                            echo trim($string);
                        }
                        ?>
                        ' size='<?php echo strlen($item -> title); ?>' readonly /></td>
                    <?php
                    } else {
                        ?>
                        <td class='align_right'>EAN or UPC</td>
                        <td><input value='<?php echo $item -> barcode; ?>' size='<?php echo strlen($string); ?>' readonly /></td>
                    <?php
                    }
                    ?>
                </tr>
            </table>
            <?php
            if ($has_variations == true) {
            ?>
            <h2>Variations</h2>
            <table class='item_details'>
                <tr>
                    <?php foreach ($variation_types as $var_type) { ?>
                        <th><?php echo ucwords($var_type); ?></th>
                    <?php } ?>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Barcode</th>
                </tr>
                <?php foreach ($variations as $variation) { ?>
                <tr>
                    <?php foreach ($variation_types as $var_type) { ?>
                        <td><input value='<?php echo extended_property_value($variation, 'var_' . $var_type); ?>' readonly /></td>
                    <?php } ?>
                    <td><input value='<?php echo $variation -> sku; ?>' readonly /></td>
                    <td><input value='<?php echo $api -> get_channel_prices($variation -> stock_id)['amazon']; ?>' readonly /></td>
                    <td><input value='<?php echo $variation -> barcode; ?>' readonly /></td>
                </tr>
                <?php } ?>
            </table>
            <?php
            }
            ?>
            <h2>Offer</h2>
            <table class='item_details'>
                <tr>
                    <td class='align_right'>Seller SKU</td>
                    <td><input value='<?php echo $item -> sku; ?>' size='<?php echo strlen($item -> sku); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Condition</td>
                    <td><input value='New' size='3' readonly /></td>
                </tr>
                <?php if ($has_variations == false) { ?>
                <tr>
                    <td class='align_right'>Price</td>
                    <?php $price = $api -> get_channel_prices($item -> stock_id)['amazon']; ?>
                    <td><input value='<?php echo $price; ?>' size='<?php echo strlen($price); ?>' readonly</td>
                </tr>
                <?php } ?>
            </table>
            <h2>Images</h2>
            <table class='item_details'>
                
            </table>
            <h2>Description</h2>
            <table class='item_details'>
                <?php
                foreach(array(1,2,3,4,5) as $i) {
                    ?>
                    <tr>
                        <td>
                            <input value='<?php echo extended_property_value($item, 'Amazon_Bullet_' . $i); ?>' size='<?php echo strlen(extended_property_value($item, 'Amazon_Bullet_' . $i)); ?>' readonly />
                        </td>
                    </tr>                    
                    <?php
                }
                ?>
            </table>
            <div class='item_details'>
                <?php echo $api -> get_channel_descriptions($item -> stock_id)['amazon']; ?>
            </div>
            <?php
            }
    }
        ?>
</div>
<?php
include($CONFIG['footer']);