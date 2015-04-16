<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

?>

<div id=login_form>
    <h2>Welcome to STC Admin</h2>
    
    <p>From here you Can:</p>
    <ul>
        <li><a href='new_product/new_product_start.php' >Add a new product to Linnworks</a></li>
        <li><a href=getsku.php >Get an unused product SKU</a></li>
    </ul>
</div>

<?php
require_once($CONFIG['footer']);