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
        <li><a href='/admin/get_csv.php' >Download current product import files</a> (A copy will be archived)</li>
        <li><a href=/admin/archive.php >Download archived product import files</a></li>
    </ul>
</div>

<?php
require_once($CONFIG['footer']);