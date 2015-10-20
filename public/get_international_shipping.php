<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

if (isset($_GET['weight'])) {
    $weight = $_GET['weight'];
}

?>

<div class=pagebox >
    <form>
        <label for=weight >Weight (g)</label>
        <input name=weight <?php if (isset($weight)) {echo "value='" . $weight . "' "; } ?>/>
        <input type=submit value="Get Shipping Prices" />
    </form>
    <br />
    <div id=result>
        <?php
            if (isset($weight)) {
                $intPostagePrices = get_international_shipping($weight);
                ?>
                <table class=form_section id=shipping_table >
                    <tr>
                        <th>France</th>
                        <th>Germany</th>
                        <th>Europe</th>
                        <th>USA</th>
                        <th>Austraila</th>
                        <th>Rest of World</th>
                    </tr>
                    <tr>
                        <td>&pound;<?php echo sprintf("%0.2f",$intPostagePrices['fr']); ?></td>
                        <td>&pound;<?php echo sprintf("%0.2f",$intPostagePrices['de']); ?></td>
                        <td>&pound;<?php echo sprintf("%0.2f",$intPostagePrices['eu']); ?></td>
                        <td>&pound;<?php echo sprintf("%0.2f",$intPostagePrices['usa']); ?></td>
                        <td>&pound;<?php echo sprintf("%0.2f",$intPostagePrices['aus']); ?></td>
                        <td>&pound;<?php echo sprintf("%0.2f",$intPostagePrices['row']); ?></td>
                    </tr>
                </table>
        <?php
            }
        ?>
    </div>
</div>

<script>
    
</script>

<?php

include($CONFIG['footer']);