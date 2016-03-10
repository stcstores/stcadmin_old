<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);
$database = new STCAdmin\Database();

?>
<form method="GET">
    <table>
        <tr>
            <td><label for="search_string">Search Title</label></td>
            <td><input type="text" name="search_string"></td>
        </tr>
        <tr>
            <td><label for="sku">Search By SKU</label></td>
            <td><input type="text" name="sku"></td>
        </tr>
        <tr>
            <td><input type="submit"></td>
        </tr>
    </table>
</form>
<br />
<?php

if (isset($_GET['search_string'])) {
    $itemList = $api->search_inventory_item_title($_GET['search_string']);
} elseif (isset($_GET['sku'])) {
    $itemList = $api->get_variation_group_inventory_item_by_SKU($_GET['sku']);
}

if (isset($itemList)) {
    writeTable($itemList);
}
function writeTable($itemList) {
    ?>
    <table class="item_details">
        <?php
        foreach ($itemList as $item) {
            ?>
            <tr>
                <td><?php echo $item['sku']; ?></td>
                <td><?php echo $item['title']; ?></td>
                <td>
                    <input type="text">
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}



include($CONFIG['footer']);
