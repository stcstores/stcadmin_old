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
            
            if ($has_variations = false) {
            ?>
            <table class='item_details'>
                <tr>
                    <td>Find Product by Barcode: </td>
                    <td><?php echo $item -> barcode; ?></td>
                </tr>
            </table>
            <?php } ?>
    
            <h2>Vital Info</h2>
            <table class='item_details'>
                <tr>
                    <td class='align_right'>Title</td>
                    <td><input value='<?php echo $item -> title; ?>' size='<?php echo strlen($item -> title); ?>' readonly /></td>
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
                    if ($has_variations = true) {
                        ?>
                        <td class='align_right'>Variation Theme</td>
                        <td><input value='<?php
                        
                        $string = '';
                        
                        $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
                        foreach ($ex_props as $prop) {
                            if ($item -> extended_property_value($item, $prop) != '') {
                                if (strlen(extended_property_value($item, $prop)) > 0) {
                                    $var_type = str_replace('var_', '', $prop);
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
            if ($has_variations = true) {
            ?>
            <h2>Variations</h2>
            <table class='item_details'>
                
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
                            <input value='<?php echo extended_property_value($item, 'Amazon Bullet ' . $i); ?>' size='<?php echo strlen(extended_property_value($item, 'Amazon Bullet ' . $i)); ?>' readonly />
                        </td>
                    </tr>
                    
                    <?php
                }
                ?>
            </table>
            <?php
            }
        }
        ?>
</div>
<?php
include($CONFIG['footer']);