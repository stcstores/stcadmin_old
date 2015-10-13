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
    
    }elseif (substr($field['field_type'], 0, 6) == 'table(' ) {            
        $table = substr($field['field_type'], 6, (strlen($field['field_type']) - 7));
        $selectInputs = getValuesFromDatabase($table, 'value');
        arrayToSelectInputs($field['field_name'] . $number, $selectInputs, $value);
    
    } elseif ($field['field_type'] == 'text') {
        echo "<input id={$field['field_name']} ";
        echo "name={$field['field_name']}{$number} type=text ";
        if ($value != 0) {
            echo "value='" . htmlspecialchars($value, ENT_QUOTES) . "' ";
        }
        echo "size={$field['size']} ";
        if ($field['required'] == true) {
            echo "required ";
        }
        echo " />";
    
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