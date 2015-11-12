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
            <?php
            foreach ($product -> keyFields as $keyField => $val) {
                if ($product -> keyFields[$keyField]) {
                    echo "<tr>\n";
                    echo "<th></th>\n";
                    echo "<th></th>\n";
                    foreach ($product -> variations as $variation) {
                        echo "<th>{$variation->details[$keyField]->text}</th>\n";
                    }
                    echo "</tr>";
                }
            }
            foreach ($fields as $field) {
                $name = $field['field_name'];
                $title = $field['field_title'];
                $type = $field['field_type'];
                echo "<tr>\n";
                echo "<td>{$field['field_title']}</td>\n";
                echo "<td>";
                if (!(array_key_exists($name, $product->keyFields)) || ($product->keyFields[$name] == false)) {
                    if ($field['field_type'] == 'checkbox') {
                        $value = 'Toggle All';
                        $class = 'toggle_all';
                    } else {
                        $value = 'Set All';
                        $class = 'set_all';
                    }
                    echo "<input class='{$class}' id='set_all-{$name}' type=button value='{$value}' />";
                }
                echo "</td>\n";
                $variation_number = 0;
                foreach ($product -> variations as $variation) {
                    $value = $variation->details[$name]->text;
                    $id = $name . '-' . $variation_number;
                    echo "<td>\n";
                    echo "<input id='{$id}' class='{$name}' type='{$type}' value='{$value}' placeholder='{$title}' ";
                    if (array_key_exists($name, $product->keyFields) && $product->keyFields[$name]) {
                        echo "disabled ";
                    }
                    if ($type == 'checkbox') {
                        if ($value == 'TRUE') {
                            echo "checked ";
                        }
                    }
                    echo "/>\n";
                    echo "</td>\n";
                    $variation_number ++;
                }
                echo "</tr>\n";
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
<?php
echo "<script>fields = ". json_encode($fields) . "</script>";
echo "<script>variation_count = {$variation_number};</script>\n";
echo "<script src='/scripts/variation_table.js' charset='utf-8'></script>\n";
include($CONFIG['footer']);
