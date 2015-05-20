function TableField(number, field, value='') {
    this.field = field;
    this.number = number;
    this.name = field['field_name'];
    this.id = this.name + this.number;
    this.type = field['field_type'];
    this.size = field['size'];
    this.value = value;
}

TableField.prototype.getInput = function() {
    this.id = this.name + this.number;
    
    var row = '<td><input name=' + this.id + ' id=' + this.id + ' type=' + this.type + ' size=35 class="' + this.name + '"value="'
    if (this.name == 'var_name') {
        row = row + '" disabled';
    } else {
        row = row + this.value + '"';
    }
    if (this.type == 'checkbox') {
        if (this.value == 'TRUE') {
            row = row + ' checked ';
        }
    }
    row = row + ' /></td>';
    return row;
}

function setVarName() {
    $(':input').each(function(){
        if ($(this).attr('class') == 'var_name') {
            var varNumber = $(this).attr('id').substring(8);
            $(this).val(getVarName(varNumber));
        }
    });
}

function getVarName(var_number) {
    value = productName;
    for (field in keyFields) {
        if (keyFields[field] == true) {
            fieldName = field;
            var keyValue = $('#' + fieldName + var_number).val();
            value = value + ' { ' + keyValue + ' } ';
        }
    }
    
    value = value + $('#var_append' + var_number).val();
    
    return value;
}

TableField.prototype.updateValue = function() {
    var input = $('#' + this.id);
    if (input.val() != null) {
        this.value = input.val();
    }
}

function TableRow(number, fields, values=null) {
    this.number = number;
    this.fields = fields;
    this.values = values;
    this.row = [];
    
    for (field in this.fields) {
        value = this.values[fields[field]['field_name']];
        row = new TableField(this.number, fields[field], value);
        this.row.push(row);
    }
}

TableRow.prototype.updateValues = function() {
    for (row in this.row) {
        this.row[row].updateValue();
    }
}

function Table(fields, values) {
    $('#var_setup_buttons').append('<td><input id="previous_page" value="<< Previous" type=submit name=previous /></td><td><input type=button value="Add" onclick="addRowsButton()" />&nbsp<input type=text size=2 name=more_var id=more_var_box />&nbspMore Variations<td><input id="next_page" value="Next >>" type=submit name=next /></td>');
    this.table = $('#var_setup');
    this.fields = fields;
    this.values = values;
    
    this.rows = [];
    
    var i = 0;
    
    for (variation in this.values) {
        row = new TableRow(i, this.fields, this.values[variation])
        this.rows.push(row);
        i++;
    }
    
    if (jQuery.isEmptyObject(this.values)) {
        this.addRows(3);
    } else if (this.values.length < 3) {
        this.addRows(3);
    }
    
    this.resetRowNumbers();
    
    this.write();
}

Table.prototype.varCount = function (){
    return this.rows.length;
}

Table.prototype.write = function() {
    this.table.empty();
    this.writeHeader();
    this.resetRowNumbers();
    setVarName();
    
    for (i in this.fields) {
        
        this.table.append('<tr id=var_setup_row_' + this.fields[i]['field_name'] + ' >');
        
        newRow = $('#var_setup_row_' + this.fields[i]['field_name']);
        
        
        
        if (i > 0) {
            if (this.fields[i]['field_type'] == 'checkbox') {
                newRow.append('<td><input type=button id=toggle_' + this.fields[i]['field_name'] + ' value="Toggle" />');
                $('#toggle_' + this.fields[i]['field_name']).click(function() {
                    toggleInternationalShipping();
            });
            } else {
                newRow.append('<td class=small_button ><input type=button value="Set All" onclick="setAllButton(\'' + this.fields[i]['field_name'] + '\')" /></td>');
            }
            if ($.inArray(this.fields[i]['field_name'], ['retail_price', 'purchase_price', 'shipping_price', 'barcode', 'var_append', 'int_shipping']) == -1){
                var checkbox = '<td class=small_button ><input type=checkbox class=set_key id=set_key_' + this.fields[i]['field_name'] + ' name="set_key_' +  this.fields[i]['field_name'] + '"' ;
                //console.log(keyFields[this.fields[i]['field_name']]);
                if (keyFields[this.fields[i]['field_name']] == true) {
                    checkbox = checkbox + ' checked ';
                }
                checkbox = checkbox + '/></td>'
                
                checkbox = $(checkbox);
                
                newRow.append(checkbox);
            } else {
                newRow.append('<td class=small_button >');
            }
            
        } else {
            newRow.append('<td class=small_button >');
            newRow.append('<td title="For more information on key fields click the question mark.">Key <a class="questionmark_link" href="/new_product/keyfields.php" >?</a></td>');
        }
        newRow.append("<td>" + this.fields[i]['field_title'] + "</td>");
        for ( variation in this.rows) {
            for ( field in this.rows[variation].row) {
                if (this.rows[variation].row[field].name == this.fields[i]['field_name']) {
                    newRow.append(this.rows[variation].row[field].getInput());
                }
            }
        }
    }
    setFormStyle();
    
    setVarName();
    
    $('.set_key').click(function() {
        var fieldName = $(this).attr('name').substring(8);
        if (keyFields[fieldName] == false) {
            keyFields[fieldName] = true;
        } else if (keyFields[fieldName] == true ){
            keyFields[fieldName] = false;
        }
        setVarName();
        //table.updateValues();
        //table.write();
    });
    
    $(':input').blur(function(){
        setVarName();
        //table.updateValues();
        //table.write();
    });
}

function toggleInternationalShipping() {
    table.updateValues();
    var setTo = ($('#int_shipping0').prop("checked"));
    if (setTo === true) {
        setTo = 'FALSE';
    } else {
        setTo = 'TRUE';
    }                

    for (row in table.rows) {
        for (field in table.rows[row].row) {
            if (table.rows[row].row[field].name == 'int_shipping') {
                table.rows[row].row[field].value = setTo;      
            } 
        }
    }
    table.write();
}

Table.prototype.resetRowNumbers = function() {
    for (i in this.rows) {
        this.rows[i].number = i;
        for (x in this.rows[i].row) {
            this.rows[i].row[x].number = i;
        }
    }
}

Table.prototype.updateValues = function() {
    for (row in this.rows) {
        this.rows[row].updateValues();
    }
}

Table.prototype.writeHeader = function() {
    this.table.append('<tr id=var_setup_header >');
    $('#var_setup_header').append('<th colspan=3 ><input type=button value="Add Another Variation" onclick="addRowButton()" /></th>');
    for (i in this.rows) {
        $('#var_setup_header').append('<th><input type=button value="Remove Variation" onclick="table.deleteRow(' + i + ')" /></th>');
    }
}

Table.prototype.deleteRow = function(number) {
    if (this.rows.length > 2) {
        this.rows.splice(number, 1);
        this.write();
    }    
}

Table.prototype.addRow = function() {
    this.rows.push(new TableRow('?', this.fields, ''));
}

function addRowButton() {
    table.updateValues();
    table.addRow();
    table.write();
}

function addRowsButton() {
    var number = $('#more_var_box').val();
    if (number == '') {
        number = 1;
    }
    table.addRows(number);
    table.write();
}

function setAllButton(field_name) {
    table.updateValues();
    var value = $('#' + field_name + '0').val();
    for (row in table.rows) {
        for (field in table.rows[row].row) {
            if (table.rows[row].row[field].name == field_name) {
                table.rows[row].row[field].value = value;
            } 
        }
    }
    table.write();
}

Table.prototype.addRows = function(number) {
    for (i = 0; i < number; i++) {
        this.addRow();
    }
    this.write();
}

$('#var_form').submit(function(){
    if (variations_form_validate() == false) {
        window.scrollTo(0, 0);
        return false;
    }
    $(':input').removeAttr('disabled');
    return true;
});



table = new Table(fields, values);

