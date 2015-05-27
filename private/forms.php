<?php

function writeFormPage($page, $product){
    echo "<form method='post' enctype='multipart/form-data'>";
    echo "<table id='basic_info' class=form_section>";
    echo "<col span='1' style='width: 10%;'><col span='1' style='width: 24%;'><col span='1' style='width: 33%;'><col span='1' style='width: 33%;'>";
    
    $fields = getFormFieldsByPage($page);
    
    foreach ($fields as $field) {
        echo "<tr>";
        echo '<td class=form_table_field_name ><label for="' . $field['field_name'] . '">' . $field['field_title'] . '</label></td>';
        echo "<td class=form_table_input>";
        
        echoInput($field, $product);
        
        echo "</td>";
        echo "<td class=form_field_table_description >{$field['field_description']}</td>";
        echo "</tr>";
        
    }
    echo "</table>";
    echo "<table class=form_nav>";
    echo "<tr><td><input value='<< Previous' type=submit name=previous /><input value='Next >>' type=submit name=next /></td></tr>";
    echo "</table>";
}

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
        echo "value='" . htmlspecialchars($value, ENT_QUOTES) . "' size={$field['size']} ";
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

function write_var_setup_page($product, $newRows=0) {
    echo "<div id=var_error class=hidden ></div>";
    $fields = getVarSetupFields();
    echo "\n<form method='post' id='var_form' enctype='multipart/form-data'>\n";
    $fields = getVarSetupFields();
    $values = getVarSetupValues();
    echo "<script>productName = '" . $product->details['item_title']->text . "';</script>\n";
    echo "<script>var fields = " . json_encode($fields) . "</script>\n";
    echo "<script>var values = " . json_encode($values) . "</script>\n";
    echo "<script>keyFields = " . json_encode($product->keyFields) . "</script>";
    echo "<table id=var_setup class=form_section >";
    echo "</table>";
    echo "<table id=var_setup_buttons class=form_nav>";
    echo "</table>";
    echo "</form>";
    echo "<script src=/scripts/var_form_validate.js></script>";
    echo "<script src=/scripts/variation_table.js></script>";
    
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

function writeVarSetup() {
    ?>
    <div class=pagebox>
        <label for=size>Add Sizes: </label>
        <input size=100 name=size id=size />
        <input type=button value=Add id=add_size_button />
        <div id=sizes>
            
        </div>
    </div>
    
    <table class=form_nav>
        <tr>
            <td>
                <input value='<< Previous' type=submit name=previous />
                <input value='Next >>' type=submit name=next />
            </td>
        </tr>
    </table>
    
    <script>
        sizes = array();
        $('#size').val('one, two, three'); // REMOVE LATER
        
        $('#add_size_button').click(function(){
            var addString = $('#size').val();
            $('#size').val('');
            
            var newSizes = addString.split(', ');
            
            console.log(sizes);
            
            for (var size in newSizes) {
                sizes.push(size);
            }
        function update() {                
                var newSize = $('<div style="display:inline-block; margin:0.5em; background:red;">');
                newSize.append('<label>' + sizes[size] + '</label>&nbsp;');
                var remove_button = $('<a>X</a>');
                
                $('#sizes').append(newSize);
            }
            
        });
    </script>
    <?php
}

?>