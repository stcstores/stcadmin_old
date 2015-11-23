<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);
?>

<div class=pagebox>
    <h2>Key Fields</h2>
    <p>Key fields are the ways in which a variation product varies.</p>
    <p>
        For instance if a shirt comes in two colours and five sizes then 'Size' and 'Colour' would be key fields. As they all share a style 'Style' would not be a key field.
        The values in the key fields become part of the variation title. Selecting the correct key fields ensures no two variations can share a title.
    </p>
</div>

<?php
require_once($CONFIG['footer']);
