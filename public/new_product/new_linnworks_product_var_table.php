<?php

    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();

if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}

if (!empty($_POST)) {
    add_variation($product);
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    }
    if (true) { // error check
        $_SESSION['new_product'] = $product;
        header('Location: imageupload.php');
        exit();
    }
}

require_once($CONFIG['header']);

$fields = array();
foreach (getVarSetupFields() as $field) {
    if (!(in_array($field['field_name'], array('var_name')))) {
        $fields[] = $field;
    }
}
$values = getVarSetupValues();
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script src="/scripts/jquery.doubleScroll.js"></script>
<h2>Set Variations for <?php echo $product->details['item_title']->text; ?></h2>
<form method="post" id="var_form" enctype="multipart/form-data">
    <div>
        <table id="var_setup" class="form_section">
            <tr>
                <th><p style="width:150px;"></p></th>
                <?php
                foreach ($product -> variations as $variation) {
                    ?>
                    <th>
                        <table class="nospace">
                            <?php
                            foreach ($product -> keyFields as $keyField => $val) {
                                if ($product -> keyFields[$keyField]) {
                                    echo "<tr class='nospace'>\n";
                                    echo "<td class='nospace'>" . ucwords($keyField) . ": </td>\n";
                                    echo "<td class='nospace'>" . $variation->details[$keyField]->text . "</td>\n";
                                    echo "</tr>\n";
                                }
                            }
                                ?>
                        </table>
                    </th>
                <?php
                }
                ?>
            </tr>
            <?php
            foreach ($fields as $field) {
                ?>
                <tr>
                    <td><?php echo $field['field_title']; ?></td>
                    <?php
                    $variation_number = 0;
                    foreach ($product -> variations as $variation) {
                        $name = $field['field_name'];
                        $title = $field['field_title'];
                        $type = $field['field_type'];
                        $value = $variation->details[$name]->text;
                        $id = $name . '-' . $variation_number;
                        echo "<td>\n";
                        echo "<input id='{$id}' class='{$name}' type='{$type}' value='{$value}' placeholder='{$title}' ";
                        if (array_key_exists($name, $product->keyFields) && $product->keyFields[$name]) {
                            echo "disabled ";
                        }
                        echo "/>\n";
                        echo "</td>\n";
                        $variation_number ++;
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <table class="form_nav">
        <tr>
            <td>
                <input value="<< Previous" type="submit" name="previous" />
                <input value="Next >>" type="submit" name="next" />
            </td>
        </tr>
    </table>
</form>

<script>
    function table_adjust(variation_count) {
        var fields = <?php echo json_encode($fields); ?>;
        var inputs = $('#var_setup input');
        var sizes = [];
        for (i=0; i<variation_count; i++) {
            sizes.push(12);
        }
        $.each(fields, function(index, field){
            sizes[field.field_name] = field.size;
        });
        inputs.each(function () {
            var id = $(this).attr('id');
            var id_array = id.split('-');
            var field = id_array[0];
            var number = id_array[1];
            var input = $(this).val();
            if(typeof input !== "undefined") {
                if (input.length > sizes[number]){
                    sizes[number] = input.length;
                }
            }
            //$(this).css('background', 'red');
        });
        inputs.each(function () {
            var id = $(this).attr('id');
            var id_array = id.split('-');
            var field = id_array[0];
            var number = id_array[1];
            $(this).attr('size', sizes[number]);
        });
    }

    $(document).ready(function() {
        table_adjust();
    });

    $('input').blur(function() {
        table_adjust(<?php echo $variation_number; ?>);
    });
</script>

<?php

include($CONFIG['footer']);

?>
