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
    return '<td><input name=' + this.id + ' id=' + this.id + ' type=' + this.type + ' size=25 value="' + this.value + '" /></td>';
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
    $('#var_setup_buttons').append('<td><input value="<< Previous" type=submit name=previous /></td><td><input type=button value="Add" onclick="addRowsButton()" />&nbsp<input type=text size=2 name=more_var id=more_var_box />&nbspMore Variations<td><input value="Next >>" type=submit name=next /></td>');
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

Table.prototype.write = function() {
    this.table.empty();
    this.writeHeader();
    this.resetRowNumbers();
    
    for (i in this.fields) {
        
        this.table.append('<tr id=var_setup_row_' + this.fields[i]['field_name'] + ' >');
        
        newRow = $('#var_setup_row_' + this.fields[i]['field_name']);
        
        if (i > 0) {
            newRow.append('<td class=small_button ><input type=button value="Set All" onclick="setAllButton(\'' + this.fields[i]['field_name'] + '\')" /></td>');
        } else {
            newRow.append('<td class=small_button >')
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
    $('#var_setup_header').append('<th colspan=2 ><input type=button value="Add Another Variation" onclick="addRowButton()" /></th>');
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

table = new Table(fields, values);