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

function get_item($sku, $api) {
    $guid = $api -> get_inventory_item_id_by_SKU($sku);
    if ($guid == null) {
        $guid = $api -> get_variation_group_id_by_SKU($sku);
        if ($guid == null) {
            no_product();
        } else {
            $has_variations = true;
        }
    } else {
        $has_variations = false;
    }

    if ($guid == null) {
        no_product();
    } else {
        $item = $api -> get_inventory_item_by_id($guid);
        $item -> has_variations = $has_variations;
        if ($has_variations) {
            add_variations_to_item($item, $api);
        }
        return $item;
    }
}

function variation_sort($item) {
    $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
    $variation_types = array();
    foreach ($ex_props as $var_type) {
        if (extended_property_value($item -> variations[0], $var_type) != '') {
            if (strlen(extended_property_value($item -> variations[0], $var_type)) > 0) {
                $variation_types[] = $var_type;
            }
        }
    }

    foreach (array_reverse($variation_types) as $var_type) {
        usort($item -> variations, function($a, $b) use ($var_type) {
            return strcmp(extended_property_value($a, $var_type), extended_property_value($b, $var_type));
        });
    }

    return $item -> variations;
}

function add_variations_to_item($item, $api) {
    $item -> variations = array();
    foreach ($api -> get_variation_children($item -> stock_id) as $guid) {
        $item -> variations[] = $api -> get_inventory_item_by_id($guid);
    }
    $item -> variations = variation_sort($item);

    $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
    $variation_types = array();
    foreach ($ex_props as $prop) {
        if (extended_property_value($item -> variations[0], $prop) != '') {
            if (strlen(extended_property_value($item -> variations[0], $prop)) > 0) {
                $var_type = str_replace('var_', '', $prop);
                $variation_types[] = $var_type;
            }
        }
    }
    $item -> variation_types = $variation_types;
    return $item;
}

function echo_vital_info($item, $api) {
    $info = array();
    $info['Title'] = $api -> get_channel_titles($item -> stock_id)['amazon'];
    $info['Manufacturer'] = extended_property_value($item, 'Manufacturer');
    $info['Brand'] = extended_property_value($item, 'Brand');
    $info['MPN'] = extended_property_value($item, 'MPN');
    $info['Material'] = extended_property_value($item, 'Material');
    $info['Size'] = extended_property_value($item, 'Size');
    if ($item -> has_variations) {
        $variations_string = '';
        foreach ($item -> variation_types as $var_type) {
            $variations_string = $variations_string . $var_type . ' ';
        }
        $info['Variation Type'] = $variations_string;
    } else {
        $info['EAN or UPC'] = $item -> barcode;
    }
    echo "<h2>Vital Info</h2>\n";
    echo "<table class='item_details'>\n";
    foreach($info as $title => $value) {?>
        <tr>
            <td class='align_right'><?php echo $title; ?></td>
            <td><input value='<?php echo $value; ?>' size='<?php echo strlen($value); ?>' readonly /></td>
        </tr>
    <?php }
    echo "</table>\n";
}

function no_product() {
    echo "<p class='error'>Product Not Found</p>\n";
    exit();
}

function echo_search_form() {
    ?>
    <form method=get >
        <label for='sku' >SKU: </label>
        <input name='sku' value='<?php if (isset($_POST['sku'])) { echo $_POST['sku']; } ?>' />
        <input type=submit value='Get Info' />
    </form>
    <?php
}

function echo_barcodes($item) {
    if (!($item -> has_variations)) {?>
        <table class='item_details'>
            <tr>
                <td>Find Product by Barcode: </td>
                <td><?php echo $item -> barcode; ?></td>
            </tr>
        </table><?php
    } else { ?>
    <p>Find Product by Barcode: </p>
    <ul class='item_details'>
        <?php foreach($item -> variations as $variation) { ?>
            <li><?php echo $variation -> barcode; ?></li>
        <?php } ?>
    </ul>
    <?php
    }
}

function echo_variation_table($item, $api) { ?>
    <table class='item_details' id='variation_table'>
    <tr>
        <?php foreach ($item -> variation_types as $var_type) { ?>
            <th><?php echo ucwords($var_type); ?></th>
        <?php } ?>
        <th>SKU</th>
        <th>EAN or UPC</th>
        <th>Condition</th>
        <th>Your Price</th>
    </tr>
    <?php
    foreach ($item -> variations as $variation) {
        $info = array();
        foreach ($item -> variation_types as $var_type) {
            $info[] = array('variation_value', extended_property_value($variation, 'var_' . $var_type));
        }
        $info[] = array('variation_sku', $variation -> sku);
        $info[] = array('variation_barcode', $variation -> barcode);
        $info[] = array('condition', 'New');
        $info[] = array('variation_price', $api -> get_channel_prices($variation -> stock_id)['amazon']);
        echo "<tr>\n";
        foreach ($info as $cell) {
            $class = $cell[0];
            $value = $cell[1];
            echo "<td><input class='{$class}' value='{$value}' readonly /></td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
}

function get_variation_string($item, $var_type) {
    foreach ($item -> variations as $variation) {
        $var_array[] = extended_property_value($variation, 'var_' . $var_type);
    }
    $var_array = array_unique($var_array);
    $varstring = '';
    foreach ($var_array as $var) {
        $varstring = $varstring . $var;
        if (!($var === end($var_array))) {
            $varstring = $varstring . ', ';
        }
    }
    return $varstring;
}

function echo_variations($item, $api) {
    echo "<h2>Variations</h2>\n";
    echo "<table class='item_details'>\n";
    foreach ($item -> variation_types as $var_type) {
        $varname = $var_type . 's';
        $varstring = get_variation_string($item, $var_type);
        ?>
        <tr>
            <td><label for='<?php echo $varname; ?>'><?php echo ucwords($varname); ?></label></td>
            <td><input value='<?php echo $varstring; ?>' readonly name='<?php echo $varname; ?>' size='<?php echo strlen($varstring); ?>' /></td>
        </tr>
        <?php
        }
    echo "</table>\n";
    echo_variation_table($item, $api);
}

function echo_offer($item, $api) {
    $sku = $item -> sku;
    $price = $api -> get_channel_prices($item -> stock_id)['amazon'];
    ?>
    <h2>Offer</h2>
    <table class='item_details'>
        <tr>
            <td class='align_right'>Seller SKU</td>
            <td><input value='<?php echo $sku; ?>' size='<?php echo strlen($sku); ?>' readonly /></td>
        </tr>
        <tr>
            <td class='align_right'>Condition</td>
            <td><input value='New' size='3' readonly /></td>
        </tr>
        <tr>
            <td class='align_right'>Price</td>
            <td><input value='<?php echo $price; ?>' size='<?php echo strlen($price); ?>' readonly</td>
        </tr>
    </table>
<?php }

function echo_images($item, $api) { ?>
    <h2>Images</h2>
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
    echo "</table>\n";
    echo "<br />\n";
    if ($item -> has_variations == true) {
        echo_variation_images($item, $api);
    }
}

function echo_variation_images($item, $api) {
    foreach ($item -> variations as $variation) {
        $images = $api -> get_image_thumbnail_urls_by_item_id($variation -> stock_id);
        if (count($images) == 0) {
            continue;
        }
        echo "<table class='item_details'>\n";
        echo "<tr>\n";
        echo "<td colspan=2 >";
        foreach ($item -> variation_types as $var_type) {
            echo extended_property_value($variation, 'var_' . $var_type) . ' ';
        }
        echo "</td>";
        echo "</tr>\n";
        foreach($images as $image) { ?>
            <tr>
                <td><a href='<?php echo $image['full']; ?>' target="_blank" ><img src='<?php echo $image['thumb']; ?>' /></a></td>
                <td><a href='<?php echo $image['full']; ?>' target="_blank" download><input type=button value='Download' /></a></td>
            </tr>
        <?php
        }
        echo "</table>\n";
        echo "<br />\n";
    }
}

function echo_description($item, $api) { ?>
    <h2>Description</h2>
    <div class='item_details'>
        <?php echo nl2br($api -> get_channel_descriptions($item -> stock_id)['amazon']); ?>
    </div>
<?php }

function echo_scripts() { ?>
    <script>
        $( document ).ready(function() {
            var cols_to_adjust = ['variation_value', 'variation_price', 'variation_sku', 'variation_barcode', 'condition'];
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
<?php }


$api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
echo "<div class=pagebox >\n";
echo_search_form();

if (isset($_POST['sku'])) {
    $sku = trim($_POST['sku'], $api);
    $item = get_item($sku);
} else if (isset($_GET['sku'])) {
    $sku = trim($_GET['sku']);
    $item = get_item($sku, $api);
}

echo_barcodes($item);
echo_vital_info($item, $api);
if ($item -> has_variations == true) {
    echo_variations($item, $api);
} else {
    echo_offer($item, $api);
}
echo_images($item, $api);
echo_description($item, $api);
echo "</div>";
echo_scripts();
include($CONFIG['footer']);
