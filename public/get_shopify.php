<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

function extended_property_value($item, $property) {
    $ex_prop = $item -> extended_properties -> get_property_by_name($property);
    if ($ex_prop != null) {
        return $ex_prop -> value;
    } else {
        return '';
    }
}

function var_sort($variations) {
    $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
    $variation_types = array();
    foreach ($ex_props as $var_type) {
        if (extended_property_value($variations[0], $var_type) != '') {
            if (strlen(extended_property_value($variations[0], $var_type)) > 0) {
                $variation_types[] = $var_type;
            }
        }
    }

    foreach (array_reverse($variation_types) as $var_type) {
        usort($variations, function($a, $b) use ($var_type) {
            return strcmp(extended_property_value($a, $var_type), extended_property_value($b, $var_type));
        });
    }

    return $variations;
}

?>

<div class=pagebox >
    <form method=get >
        <label for='sku' >SKU: </label>
        <input name='sku' value='<?php if (isset($_POST['sku'])) { echo $_POST['sku']; } ?>' />
        <input type=submit value='Get Info' />
    </form>

    <?php

    if (isset($_POST['sku'])) {
        $sku = trim($_POST['sku']);
    } else if (isset($_GET['sku'])) {
        $sku = trim($_GET['sku']);
    }

    if (isset($sku)) {
        $api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);

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

            if ($has_variations == true) {
                $variations = array();
                foreach ($api -> get_variation_children($item -> stock_id) as $guid) {
                    $variations[] = $api -> get_inventory_item_by_id($guid);
                }
                $variations = var_sort($variations);
            }
            ?>
            <table class='item_details'>
                <tr>
                    <td class='align_right'>Title</td>
                    <td><input value='<?php
                    $title = $api -> get_channel_titles($item -> stock_id)['shopify'];
                    echo $title; ?>' size='<?php echo strlen($title); ?>' readonly /></td>
                </tr>
                <tr>
                    <td class='align_right'>Vendor</td>
                    <td><input value='<?php echo extended_property_value($item, 'Manufacturer'); ?>' size='<?php echo strlen(extended_property_value($item, 'Manufacturer')); ?>' readonly /></td>
                </tr>
            </table>
            <div class='item_details'>
                <?php echo $api -> get_channel_descriptions($item -> stock_id)['shopify']; ?>
            </div>
            <br />
            <table class='item_details'>
                <tr>
                    <td class='align_right'>SKU</td>
                    <td><input value='<?php echo $item -> sku; ?>' size='<?php echo strlen($item -> sku); ?>' readonly /></td>
                </tr>
                <?php if ($has_variations == false) {
                    ?>
                <tr>
                    <td class='align_right'>Price</td>
                    <?php $price = $api -> get_channel_prices($item -> stock_id)['shopify']; ?>
                    <td><input value='<?php echo $price; ?>' size='<?php echo strlen($price); ?>' readonly</td>
                </tr>
                <?php } ?>
            </table>
            <?php
            if ($has_variations == true) {
            ?>
            <h2>Variations</h2>
            <table class='item_details'>
                <?php
                $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
                $variation_types = array();
                foreach ($ex_props as $prop) {
                    if (extended_property_value($variations[0], $prop) != '') {
                        if (strlen(extended_property_value($variations[0], $prop)) > 0) {
                            $variation_types[] = $prop;
                        }
                    }
                }
                foreach ($variation_types as $var_type) {
                    $var_array = array();
                    foreach ($variations as $variation) {
                        $var_array[] = extended_property_value($variation, $var_type);
                    }
                    $var_array = array_unique($var_array);
                    $varname = str_replace('var_', '', $var_type) . 's';
                    $varstring = '';
                    foreach ($var_array as $var) {
                        $varstring = $varstring . $var;
                        if (!($var === end($var_array))) {
                            $varstring = $varstring . ', ';
                        }
                    }
                    ?>
                    <tr>
                        <td><label for='<?php echo $varname; ?>'><?php echo ucwords($varname); ?></label></td>
                        <td><input value='<?php echo $varstring; ?>' name='<?php echo $varname; ?>' size='<?php echo strlen($varstring); ?>' /></td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <table class='item_details' id='variation_table'>
                <tr>
                    <?php foreach ($variation_types as $var_type) { ?>
                        <th><?php echo ucwords(str_replace('var_', '', $var_type)); ?></th>
                    <?php } ?>
                    <th>SKU</th>
                    <th>Your Price</th>
                </tr>
                <?php foreach ($variations as $variation) { ?>
                <tr>
                    <?php foreach ($variation_types as $var_type) { ?>
                        <td><input class='variation_value' value='<?php echo extended_property_value($variation, $var_type); ?>' readonly /></td>
                    <?php } ?>
                    <td><input class='variation_sku' value='<?php echo $variation -> sku; ?>' readonly /></td>
                    <td><input class='variation_price' value='<?php echo $api -> get_channel_prices($variation -> stock_id)['shopify']; ?>' readonly /></td>
                </tr>
                <?php } ?>
            </table>
            <h2>Images</h2>
            <?php
            }
            if ($has_variations == false) {
            ?>


            <table class='item_details'>
                <?php
                $images = $api -> get_image_thumbnail_urls_by_item_id($item -> stock_id);
                foreach($images as $image) {
                    ?>
                    <tr>
                        <td><a href='<?php echo $image['full']; ?>' target="_blank" ><img src='<?php echo $image['thumb']; ?>' /></a></td>
                        <td><a href='<?php echo $image['full']; ?>' target="_blank" download><input type=button value='Download' /></a></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
            }
            if ($has_variations == true) {
                foreach ($variations as $variation) {
                    $images = $api -> get_image_thumbnail_urls_by_item_id($variation -> stock_id);
                    if (count($images) > 0) {
                        ?>
                        <table class='item_details'>
                        <?php
                        echo "<td colspan=2 >";
                        foreach ($variation_types as $var_type) {
                            echo extended_property_value($variation, 'var_' . $var_type) . ' ';
                        }
                        echo "</td>";
                        ?>

                        <?php foreach($images as $image) { ?>
                            <tr>
                                <td><a href='<?php echo $image['full']; ?>' target="_blank" ><img src='<?php echo $image['thumb']; ?>' /></a></td>
                                <td><a href='<?php echo $image['full']; ?>' target="_blank" download><input type=button value='Download' /></a></td>
                            </tr>
                        <?php
                        }
                    } ?>
                    </table>
                    <br />
            <?php
                }
            }
        }
    }
        ?>
</div>
<script>
    $( document ).ready(function() {
        var cols_to_adjust = ['variation_value', 'variation_price', 'variation_sku', 'variation_barcode'];
        for (column in cols_to_adjust) {
            var col = cols_to_adjust[column];
            col_size = 3;
            $('.' + col).each(function(){
                var current_size = $(this).val().length;
                if (current_size > col_size) {
                    col_size = current_size;
                }
            });
            $('.' + col).each(function(){
                $(this).attr('size', col_size)
            });
        }
    });
</script>
<?php
include($CONFIG['footer']);
