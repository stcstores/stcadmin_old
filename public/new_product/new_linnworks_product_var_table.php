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

if (isset($_POST['variation_details'])) {
    $variationDetails = json_decode($_POST['variation_details'], true);
    add_variation($product, $variationDetails);

    if (isset($_POST['next'])) {
        header('Location: imageupload.php');
        exit();
    } else {
        header('Location: new_linnworks_product_1_basic_info.php');
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
<?php
if (!(producExists($product))) {
    echo "<p><input id='reset_variations' type=button value='Reset Variations' /> <span class=error>Warning: This will delete any existing variations</span></p>";
}
?>
<div id="errors"></div>
<form method="post" id="var_form" enctype="multipart/form-data">
    <table id="var_setup" class="form_section">
        <?php
        $variation_strings = array();
        foreach ($product -> keyFields as $keyField => $val) {
            if ($product -> keyFields[$keyField]) {
                echo "<tr>\n";
                echo "<th></th>\n";
                echo "<th></th>\n";
                foreach ($product -> variations as $variation) {
                    $variation_string = $variation->details[$keyField]->text;
                    echo "<th>{$variation_string}</th>\n";
                    $variation_strings[] = $variation_string;
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
                echo "<input id='{$id}' class='{$name}' type='{$type}' value='{$value}' ";
                echo "placeholder='{$title}' autocomplete='off' ";
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
$max_variation_string_length = max(array_map('strlen', $variation_strings));

$numericFields = array();
foreach ($product->variations[0]->details as $key => $detail) {
    if ($detail instanceof NumericDetail) {
        $numericFields[] = $key;
    }
}

echo "<script>\n";
echo "\tdefault_col_size = ". $max_variation_string_length . ";\n";
echo "\tfields = ". json_encode($fields) . ";\n";
echo "\tvariation_count = {$variation_number};\n";
echo "\tnumericFields = " . json_encode($numericFields) . ";\n";
echo "</script>";
echo "<script src='/scripts/formstyle.js' charset='utf-8'></script>\n";
echo "<script src='/scripts/variation_table.js' charset='utf-8'></script>\n";
echo "<script src='/scripts/var_form_validate.js' charset='utf-8'></script>\n";
include($CONFIG['footer']);
