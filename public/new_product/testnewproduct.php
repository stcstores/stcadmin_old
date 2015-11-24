<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);

echo "\t\t\t<div id=testproduct>\n";
if (!isset($_SESSION['new_product'])) {
    echo "No Object";
} else {
    echo "\t\t\t\t<input type=button value='Write Product' id=write_product />\n";
    echo "\t\t\t\t\t<table>\n";
    echo "\t\t\t\t\t\t<tr>\n";
    echo "\t\t\t\t\t\t\t<th>Detail</th>\n\t\t\t\t\t\t\t<th>Text</th>\n\t\t\t\t\t\t\t<th>Value</th>\n";
    echo "\t\t\t\t\t\t</tr>\n";
    $product = $_SESSION['new_product'];
    foreach ($product->details as $detail) {
        echo "\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t<td>";
        echo $detail->name;
        echo "</td>\n\t\t\t\t\t\t\t<td>";
        echo htmlspecialchars($detail->text);
        echo "</td>\n\t\t\t\t\t\t\t<td>";
        if (is_array($detail->value)) {
            echo "Array()";
        } else {
            echo htmlspecialchars($detail->value);
        }
        echo "</td>\n\t\t\t\t\t\t</tr>\n";
    }
    echo "\t\t\t\t\t</table>\n";
    echo "\t\t\t\t\t<br />\n";
    if (count($product->variations) > 0) {
        echo "\t\t\t\t\t<table id=testvar>\n";
        echo "\t\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t\t<th>Variation Number</th>\n";
        foreach ($product->variations[0]->details as $detail => $value) {
            echo "\t\t\t\t\t\t\t<th>{$detail}</th>\n";
        }
        echo "\t\t\t\t\t\t</tr>\n";
        foreach ($product->variations as $key => $variation) {
            echo "\t\t\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t\t\t<td>{$key}</td>\n";
            foreach ($variation->details as $detail => $value) {
                echo "\t\t\t\t\t\t\t<td>" . htmlspecialchars($value->text) . "</td>\n";
            }
            echo "\t\t\t\t\t\t</tr>\n";
        }
        echo "\t\t\t\t\t\t</tr>\n";
        echo "\t\t\t\t\t</table>\n";
    }
    echo "\t\t\t\t</div>\n";
    echo "\t\t\t\t<div>\n";
    echo "\t\t\t\t\t<table>\n";
    if (count($product->variations) > 0) {
        foreach ($product->variations as $var) {
            echo "\t\t\t\t\t\t<tr>\n";
            foreach ($var->images->images as $image) {
                echo "\t\t\t\t\t\t\t<td><img src='<?php echo $image->thumbPath; ?>' /></td>\n";
            }
            echo "\t\t\t\t\t\t</tr>\n";
        }
    } else {
        echo "\t\t\t\t\t\t<tr>\n";
        foreach ($product->images as $image) {
                echo "\t\t\t\t\t\t\t<td><img src='<?php echo $image->thumbPath; ?>' /></td>\n";
        }
        echo "\t\t\t\t\t\t</tr>\n";
    }
    echo "\t\t\t\t\t</table>\n";
    echo "\t\t\t\t</div>\n";
    echo "\t\t\t\t<table>\n";
    echo "\t\t\t\t\t<tr>\n";
    echo "\t\t\t\t\t\t<th>Field</th>\n";
    echo "\t\t\t\t\t\t<th>is Key</th>\n";
    echo "\t\t\t\t\t</tr>\n";
    foreach ($product->keyFields as $field => $value) {
        echo "\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t<td>" . $field . "</td>\n";
        echo "\t\t\t\t\t\t<td>";
        if ($value == true) {
            echo 'Yes';
        } elseif ($value == false) {
            echo 'No';
        } else {
            echo '???';
        }
        echo "</td>\n";
        echo "\t\t\t\t\t</tr>\n";
    }
    echo "\t\t\t\t</table>\n";
        ?>
                <script>
                    $('#write_product').click(function() {
                        $.ajax({
                            url: 'writeproduct.php',
                            async: false,
                            dataType: 'json',
                            success: function(data){
                            }
                        });
                    });
                </script>
    <?php
}

require_once($CONFIG['footer']);
