<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

$database = new STCAdmin\Database();
$query = "SELECT id, header FROM stcadmin_news WHERE display=TRUE ORDER BY timestamp DESC LIMIT 50;";
$result = $database -> selectQuery($query);

?>

<div class=pagebox>
    <h2>Welcome to STC Admin</h2>

    <p>From here you Can:</p>
    <ul>
        <li><a href='new_product/new_product_start.php' >Add a new product to Linnworks</a></li>
        <li><a href=getsku.php >Get an unused product SKU</a></li>
        <li><a href='get_international_shipping.php' >Find international shipping rates for products</a></li>
        <li><a href='get_amazon.php' >Get details of a product for listing on Amazon</a></li>
    </ul>
</div>
<br />
<div class=pagebox id=news>
    <h3>Updates</h3>
    <ul>
        <?php
        foreach ($result as $record) {
            ?>
            <li><a href='/admin/news.php?id=<?php echo $record['id']; ?>' ><?php echo $record['header']; ?></a></li>
            <?php
        }
        ?>
    </ul>
</div>

<?php
require_once($CONFIG['footer']);
