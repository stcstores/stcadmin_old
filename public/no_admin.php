<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);
?>

<h3>Admin Reqiured</h3>
<p>You lack the administration privlidges to access this area</p>
<p><a href=index.php >Home</a></p>