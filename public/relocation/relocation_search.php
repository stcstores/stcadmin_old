<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);
$database = new STCAdmin\Database();
$categoryLookup = array();
$categoryInfo = $api->get_category_info();
foreach ($categoryInfo as $category) {
    $categoryLookup[$category['id']] = $category['name'];
}$api = new LinnworksAPI\LinnworksAPI($_SESSION['username'], $_SESSION['password']);
$database = new STCAdmin\Database();
$categoryLookup = array();
$categoryInfo = $api->get_category_info();
foreach ($categoryInfo as $category) {
    $categoryLookup[$category['id']] = $category['name'];
}


function writeTable($itemList, $categoryLookup)
{
    ?>
    <?php
    $titleLength = 50;
    foreach ($itemList as $item) {
        if (strlen($item['Title']) > $titleLength - 15) {
            $titleLength = strlen($item['Title']) + 15;
        }
    }
    foreach ($itemList as $item) {
        ?>
        <tr class="item_row">
            <td><?php echo $item['SKU']; ?></td>
            <td><input class="item_title" value="<?php echo $item['Title']; ?>" size="<?php echo $titleLength; ?>"/></td>
            <td>
                <input class="binrack_value" id="<?php echo $item['Id']; ?>" type="text" value="">
            </td>
            <td><?php echo $categoryLookup[$item['Category']]; ?></td>
        </tr>
        <?php
    }
}

if ((isset($_POST['search_string']) && strlen($_POST['search_string']) > 0) || (isset($_POST['sku']) && strlen($_POST['sku']) > 0)) {
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
            "Width" => 250,
            "IsEditable" => true),
        array("ColumnName" => "Category",
            "DisplayName" => "Category",
            "Group" => "General",
            "Field" => "String",
            "SortDirection" => "None",
            "Width" => 250,
            "IsEditable" => true),
        );

    $view = $api -> get_new_inventory_view();
    $view['Id'] = "fce96d68-55bc-4ebb-bd34-4b6eab71f5f0";
    $view['Name'] = "Bin/Rack";
    $view['Mode'] = "All";
    $view['Source'] = "All";
    $view['SubSource'] = "Show all";
    $view['CountryCode'] = "All";
    $view['Listing'] = "All";
    $view['ShowOnlyChanged'] = false;
    $view['IncludeProducts'] = "All";
    $view['Columns'] = $columns;
    $filter = array();
    if (isset($_POST['search_string']) && strlen($_POST['search_string']) > 0) {
        $filter['Value'] = $_POST['search_string'];
        $filter['FilterName'] = 'Title';
        $filter['Condition'] = 'Contains';
    } elseif (isset($_POST['sku'])&& strlen($_POST['sku']) > 0) {
        $filter['Value'] = $_POST['sku'];
        $filter['FilterName'] = 'SKU';
        $filter['Condition'] = 'Equals';
    }
    $filter['Field'] = 'String';
    $filter['FilterNameExact'] = '';

    $view['Filters'] = [$filter];

    $response = $api -> get_inventory_items(0, 0, $view);
    $itemList = $response['Items'];
    writeTable($itemList, $categoryLookup);
} else {
    echo "ERROR";
}
