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
        <li><a href='/admin/archive.php' >View and download archived new products</a> (A copy will be archived)</li>
    </ul>
</div>

<?php
require_once($CONFIG['footer']);