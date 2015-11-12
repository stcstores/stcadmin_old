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
    <div class="var_table_container">
        <table id="var_setup" class="form_section">
            <tr>
                <th><p style="width:150px;"></p></th>
                <?php
                foreach ($fields as $field) {
                    echo "<th>" . $field['field_title'] . "</th>\n";
                }
                ?>
            </tr>
            <?php
            foreach ($product -> variations as $variation) {
                ?>
                <tr>
                    <td class="headcol">
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
                    </td>
                    <?php
                    foreach ($fields as $field) {
                        $name = $field['field_name'];
                        $title = $field['field_title'];
                        $type = $field['field_type'];
                        $value = $variation->details[$name]->text;
                        echo "<td>\n";
                        echo "<input class='{$name}' type='{$type}' value='{$value}' placeholder='{$title}' ";
                        if (array_key_exists($name, $product->keyFields) && $product->keyFields[$name]) {
                            echo "disabled ";
                        }
                        echo "/>\n";
                        echo "</td>\n";
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
    function table_adjust() {
        var fields = <?php echo json_encode($fields); ?>;
        var lock_cols = [];
        var cols_to_adjust = [];
        for (i=0; i<fields.length; i++) {
            if (lock_cols.indexOf(fields[i].field_name) === -1) {
                console.log('hello');
                cols_to_adjust.push(fields[i]);
            }
        }
        for (i=0; i<cols_to_adjust.length; i++) {
            var col = cols_to_adjust[i].field_name;
            col_size = cols_to_adjust[i].size;
            $('.' + col).each(function(){
                var current_size = $(this).val().length;
                if (current_size > col_size) {
                    col_size = current_size;
                }
            });
            $('.' + col).each(function(){
                $(this).attr('size', col_size)
            });
        }
    }

    $(document).ready(function() {
        table_adjust();
    });

    $('input').blur(function() {
        table_adjust();
    });
</script>

<?php

include($CONFIG['footer']);

?>
