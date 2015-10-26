<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

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

<?php
require_once($CONFIG['footer']);