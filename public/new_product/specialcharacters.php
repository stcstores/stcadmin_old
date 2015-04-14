<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
require_once($CONFIG['check_login']);
require_once($CONFIG['header']);
?>

<div class="pagebox">
    <h2>Special Characters</h2>
    
    <p>The following is a list of 'special characters' which are not allowed in certain fields and will be removed there from.</p>
    
    <?php
    
    $specialCharacters = getSpecialCharacters();
    
    echo "<table>";
    
    foreach ($specialCharacters as $char) {
        echo "<tr><td>" . $char['name'] . "</td><td>" . $char['sc'] . "</td></tr>";
    }
    
    echo "</table>";
    
    ?>

</div>