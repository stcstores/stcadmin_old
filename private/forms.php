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

function writeVarSetup($product, $newRows=0) {
    $fields = getVarSetupFields();
    $values = getVarSetupValues();
    echo "<script>productName = '" . $product->details['item_title']->text . "';</script>\n";
    echo "<script>var fields = " . json_encode($fields) . "</script>\n";
    echo "<script>var values = " . json_encode($values) . "</script>\n";
    echo "<script>keyFields = " . json_encode($product->keyFields) . "</script>\n";
    ?>
    <div class="pagebox">
        <h2>Set Variations for <?php echo $product->details['item_title']->text; ?></h2>
        <div>
            <table id="add_variation_types" class="form_section">
                
            </table>
        </div>
        <br />
        <div>
            <table id="add_variations" class="form_section">
                
            </table>
        </div>
        <div>
            <table id="list_of_variations" class="form_section">
                
            </table>
        </div>
        <br />
        <div id="var_error" class="hidden" ></div>
        <form method="post" id="var_form" enctype="multipart/form-data">
            <div class="variation_table">
                <table id="var_setup" class="form_section" >
                    
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
    </div>
    
    <script src="/scripts/var_form_validate.js"></script>
    <script src="/scripts/variation_table.js"></script>
    
    <?php
}