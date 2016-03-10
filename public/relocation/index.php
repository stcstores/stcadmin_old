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

if (1) {
    $columns = array(
        array("ColumnName" => "SKU",
            "DisplayName" => "SKU",
            "Group" => "General",
            "Field" => "String",
            "SortDirection" => "None",
            "Width" => 150,
            "IsEditable" => false),
        array("ColumnName" => "Title",
            "DisplayName" => "Title",
            "Group" => "General",
            "Field" => "String",
            "SortDirection" => "None",
            "Width" => 250,
            "IsEditable" => true),
        array("ColumnName" => "BinRack",
            "DisplayName" => "BinRack",
            "Group" => "Location",
            "Field" => "String",
            "SortDirection" => "None",
            "Width" => 75,
            "IsEditable" => true)
        );

    $view = $api -> get_new_inventory_view();
    $view['Columns'] = $columns;
    $filter = array();
    if (isset($_GET['search_string']) && strlen($_GET['search_string']) > 0) {
        $filter['Value'] = $_GET['search_string'];
        $filter['FilterName'] = 'Title';
        $filter['Condition'] = 'Contains';
    } elseif (isset($_GET['sku'])&& strlen($_GET['sku']) > 0) {
        $filter['Value'] = $_GET['sku'];
        $filter['FilterName'] = 'SKU';
        $filter['Condition'] = 'Equals';
    }
    $filter['Field'] = 'String';
    $filter['FilterNameExact'] = '';

    $view['Filters'] = [$filter];

    $response = $api -> get_inventory_items(0, 0, $view);
    $itemList = $response['Items'];
    writeTable($itemList);
}

function writeTable($itemList)
{
    ?>
    <table class="item_details">
        <tr>
            <td></td>
            <td></td>
            <td><button>Update</button></td>
        </tr>
        <?php
        foreach ($itemList as $item) {
            ?>
            <tr>
                <td><?php echo $item['SKU']; ?></td>
                <td><?php echo $item['Title']; ?></td>
                <td>
                    <input type="text" value="<?php echo $item['BinRack']; ?>">
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td><button>Update</button></td>
        </tr>
    </table>
    <?php
}



include($CONFIG['footer']);
