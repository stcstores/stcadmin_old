<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);


?>

<form method="GET" id="item_search">
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
            <td><input type="submit" id="search_button"></td>
        </tr>
    </table>
</form>
<br />
<?php

?>
<table class="item_details">
    <thead>
        <tr>
            <td>SKU</td>
            <td>Title</td>
            <td><button class="update_button">Update</button></td>
            <td>Category</td>
        </tr>
    </thead>
    <tbody id="item_rows">

    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td><button class="update_button">Update</button></td>
        </tr>
    </tfoot>
</table>
<script src="/scripts/relocation.js"></script>
<?php

include($CONFIG['footer']);
