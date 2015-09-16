function TableRow(number, fields, values=null) {
    this.number = number;
    this.fields = fields;
    this.values = values;
    this.row = [];
}

Table.prototype.updateValues = function() {
    for (variation in variations.variations) {
        for (detail in variations.variations[variation].details) {
            variations.variations[variation].details[detail].updateValue();
        }
        variations.variations[variation].updateTitle();
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
    this.resetRowNumbers();
    
    for (i in this.fields) {
        
        this.table.append('<tr id=var_setup_row_' + this.fields[i]['field_name'] + ' >');
        
        newRow = $('#var_setup_row_' + this.fields[i]['field_name']);
        
        if (i > 0) {
            if (this.fields[i]['field_type'] == 'checkbox') {
                var toggleButton = $('<input type=button id=toggle_' + this.fields[i]['field_name'] + ' value="Toggle All" title="Toggles international shipping on or off for all variations."/>');
                toggleButton.click(toggleButtonGenerator(this.fields[i]['field_name']));
                var toggleField = $('<td>').append(toggleButton);
                newRow.append(toggleField);
            } else {
                var setAllButton = $('<input type=button title="Sets ' + this.fields[i]['field_title'] + ' for every variation to match the left most." value="Set All" />')
                setAllButton.click(setAllButtonGenerator(this.fields[i]['field_name']));
                var buttonField = $('<td class="small_buton">').append(setAllButton);
                newRow.append(buttonField);
            }
        } else {
            newRow.append('<td class=small_button >');
        }
        newRow.append('<td title="' + this.fields[i]['field_description'] + '" >' + this.fields[i]['field_title'] + '</td>');
        for ( avariation in variations.variations) {
            var variation = variations.variations[avariation];
            if (variation.active) {
                for ( afield in variation.details) {
                    var field = variation.details[afield];
                    if (field.name == this.fields[i]['field_name']) {
                        newRow.append(field.getInput());
                    }
                }
            }
        }
    }
    
    setFormStyle();
    
    for (i=0; i < variations.variations.length; i++) {
        $('#var_append' + i).blur(resetTableGenerator());
    }
    
    
}

function resetTableGenerator() {
    return function() {
        table.updateValues();
        table.write();
    }
}

function toggleButtonGenerator(field_name) {
    return function(event){
        table.updateValues();
        var setTo = variations.variations[0].details[field_name].value;
        setTo = !setTo;
        for (variation in variations.variations) {
            variations.variations[variation].details[field_name].value = setTo;
        }
        table.write();
    }
}

Table.prototype.resetRowNumbers = function() {
    for (i in this.rows) {
        this.rows[i].number = i;
        for (x in this.rows[i].row) {
            this.rows[i].row[x].number = i;
        }
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

function setAllButtonGenerator(field_name) {
    return function(event){
        table.updateValues();
        for (variation in variations.variations) {
            variations.variations[variation].details[field_name].value = variations.variations[0].details[field_name].value;
        }
        table.write();
    }
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
    for(varType in variations.variationTypes){
        $('#var_form').append('<input name="var_' + varType + '" value="' + variations.variationTypes[varType].used + '" />');
    }
    $(':input').removeAttr('disabled');
    return true;
});