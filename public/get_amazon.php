<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

?>

<div class=pagebox >
    <form method=post >
        <label for='sku' >SKU: </label>
        <input name='sku' <?php if (isset($_POST['sku'])) { echo $_POST['sku']; } ?> />
        <input type=submit value='Get Info' />
    </form>
    
    <?php
    
    if (isset($_POST['sku'])) {
        
        $sku = $_POST['sku'];
        
        $api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);
        
        $guid = $api -> getInventoryItemIdBySKU($sku);
        
        $item = $api -> get_inventory_item_by_id($guid);
        
        ?>
        <h2>Vital Info</h2>
        <table>
            <tr>
                <td>Title</td>
                <td><?php echo $item -> title; ?></td>
            </tr>
            <tr>
                <td>Manufacturer</td>
                <td><?php echo $item -> extended_properties -> get_property_by_name('Manufacturer') -> value; ?></td>
            </tr>
            <tr>
                <td>Brand</td>
                <td><?php echo $item -> extended_properties -> get_property_by_name('Brand') -> value; ?></td>
            </tr>
            <tr>
                <td>Manufacturer Part Number</td>
                <td><?php echo $item -> extended_properties -> get_property_by_name('MPN') -> value; ?></td>
            </tr>
            <tr>
                <td>Material</td>
                <td><?php echo $item -> extended_properties -> get_property_by_name('Material') -> value; ?></td>
            </tr>
            <tr>
                <td>Size</td>
                <td><?php echo $item -> extended_properties -> get_property_by_name('Size') -> value; ?></td>
            </tr>
            <tr>
                <td>Variation Theme</td>
                <td><?php
                
                $string = '';
                
                $ex_props = array('var_size', 'var_colour', 'var_design', 'var_age', 'var_shape', 'var_style', 'var_material', 'var_texture');
                foreach ($ex_props as $prop) {
                    if ($item -> extended_properties -> get_property_by_name($prop) != null) {
                        if (strlen($item -> extended_properties -> get_property_by_name($prop)->value) > 0) {
                            $var_type = str_replace('var_', '', $prop);
                            $string = $string . $var_type . ' ';
                        }
                    }
                }
                echo $string;
                ?></td>
            </tr>
            <tr>
                <td>EAN or UPC</td>
                <td><?php echo $item -> barcode; ?></td>
            </tr>
        </table>
        <h2>VARIATIONS</h2>
        <h2>Offer</h2>
        <table>
            <tr>
                <td>Seller SKU</td>
                <td><?php echo $item -> sku; ?></td>
            </tr>
            <tr>
                <td>Condition</td>
                <td>New</td>
            </tr>
        </table>
        <?php
    }
    ?>
</div>
<?php
include($CONFIG['footer']);