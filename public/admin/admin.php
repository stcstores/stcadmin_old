<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

?>

<div class=pagebox>
    <h2>STC Admin Administration</h2>
    
    <p>From here you Can:</p>
    <ul>
        <li><a href='/admin/new_user.php' >Add a new user to STC Admin</a></li>
        <li><a href=getsku.php >Download archived new product import files</a></li>
    </ul>
</div>

<?php
require_once($CONFIG['footer']);