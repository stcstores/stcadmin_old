<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);


echo "<div id=testproduct>";
if (!isset($_SESSION['new_product'])) {
    echo "No Object";
} else {
    echo "<input type=button value='Write Product' id=write_product />";
    echo "<table>";
    echo "<tr>";
    echo "<th>Detail</th><th>Text</th><th>Value</th>";
    echo "</tr>";
    $product = $_SESSION['new_product'];
    foreach ($product->details as $detail ) {
        
        echo "<tr><td>" . $detail->name . "</td><td>" . htmlspecialchars($detail->text) . "</td><td>";
        if (is_array($detail->value)) {
            echo "Array()";
        } else {
            echo htmlspecialchars($detail->value);
        }
        echo "</td></tr>";
    }
    echo "</table>";
    
    echo "<br />";
    
    if (count($product->variations) > 0) {
        echo "<table id=testvar>";
        echo "<tr>";
        echo "<th>Variation Number</th>";
        foreach ($product->variations[0]->details as $detail=>$value) {
            echo "<th>{$detail}</th>";
        }
        echo "</tr>";
        foreach ($product->variations as $key=>$variation) {
            echo "<tr>";
            echo "<td>{$key}</td>";
            foreach ($variation->details as $detail=>$value) {
                echo "<td>" . htmlspecialchars($value->text) . "</td>";               
            }
            echo "</tr>";
        }
        echo "</tr>";
        echo "</table>";
    }
    echo "</div>";
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