<?php

function echoInput($field, $product, $number='') {
    if ($product != null) {
        $value = $product->details[$field['field_name']]->text;
    } else {
        $value = '';
    }
    
    if ($field['field_type'] == 'file multiple') {
        $name = substr($field['field_name'], 0, -2) . $number . '[]';
        echo "<input id={$field['field_name']} ";
        echo "name={$name} type=file multiple";
        echo " />";
    
    } elseif ($field['field_type'] == 'text') {
        ?><input id="<?php echo $field['field_name']; ?>" name="<?php echo $field['field_name'] . $number;?>" type="text" <?php
        if ((!is_numeric($value)) or (is_numeric($value) and $value != 0)) {
            ?> value="<?php echo htmlspecialchars($value, ENT_QUOTES); ?>"<?php
            } ?> size="<?php echo $field['size']; ?>" <?php if ($field['required'] == true) { echo "required "; } ?>/>
        <?php
    
    } elseif ($field['field_type'] == 'checkbox') {
        echo "<input name={$field['field_name']}{$number} type={$field['field_type']} ";
        if (($value == true) && (!($value == 'FALSE'))){
            echo "checked ";
        }
        echo "/>";
    
    } elseif($field['field_type'] == 'textarea') {
        echo "<textarea rows=4 cols=45 id={$field['field_name']} name={$field['field_name']}{$number} ";
        if ($field['required'] == true) {
            echo "required ";
        }
        echo ">";
        echo $value;
        echo "</textarea>";
    } else {
        echo "<input name={$field['field_name']} type={$field['field_type']} size={$field['size']} />\n";
    }
}

function echoVarSetupRow($fields, $i, $variation) {
    echo "<tr id=var_row_{$i} >";
    echo "<td><input type=submit value='Remove Variation' name=remove{$i} /></td>";
    
    foreach ($fields as $field) {
        echo "<td>";
        echoInput($field, $variation, $i);
        echo "</td>";
    }
    
    echo "</tr>";
}