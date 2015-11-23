<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

if (isset($_GET['weight'])) {
    $weight = $_GET['weight'];
} else {
    $weight = '';
}

echo "\t\t\t<div class=pagebox >\n";
echo "\t\t\t\t<form>\n";
echo "\t\t\t\t\t<label for=weight >Weight (g)</label>\n";
echo "\t\t\t\t\t<input name=weight " . $weight . " />\n";
echo "\t\t\t\t\t<input type=submit value='Get Shipping Prices' />\n";
echo "\t\t\t\t</form>\n";
echo "\t\t\t\t<br />\n";
echo "\t\t\t\t<div id=result>\n";
if (isset($weight)) {
    $shippingTable = new STCAdmin\CSV\InternationalShippingLookup();
    $intPostagePrices = $shippingTable->getInternationalShipping($weight);
    echo "\t\t\t\t\t<table class=form_section id=shipping_table >\n";
    echo "\t\t\t\t\t\t<tr>\n";
    echo "\t\t\t\t\t\t\t<th>France</th>\n";
    echo "\t\t\t\t\t\t\t<th>Germany</th>\n";
    echo "\t\t\t\t\t\t\t<th>Europe</th>\n";
    echo "\t\t\t\t\t\t\t<th>USA</th>\n";
    echo "\t\t\t\t\t\t\t<th>Austraila</th>\n";
    echo "\t\t\t\t\t\t\t<th>Rest of World</th>\n";
    echo "\t\t\t\t\t\t</tr>\n";
    echo "\t\t\t\t\t\t<tr>\n";
    foreach (array('fr', 'de', 'eu', 'usa', 'aus', 'row') as $countryCode) {
        echo "\t\t\t\t\t\t\t<td>&pound;" . sprintf("%0.2f", $intPostagePrices[$countryCode]) . "</td>\n";
    }
    echo "\t\t\t\t\t\t</tr>\n";
    echo "\t\t\t\t\t</table>\n";
}
echo "\t\t\t\t</div>\n";
echo "\t\t\t</div>\n";
include($CONFIG['footer']);
