<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);
?>

<div class=pagebox>
    <h3>Admin Reqiured</h3>
    <p>You lack the administration privlidges to access this area</p>
    <p><a href=index.php >Return to homepage</a></p>
</div>