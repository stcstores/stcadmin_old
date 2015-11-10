function VariationField(number, field, value, disable) {
    if (value === undefined) {
	value = '';
    }
    this.field = field;
    this.number = number;
    this.name = field.field_name;
    this.title = field.field_title;
    this.id = this.name + this.number;
    this.type = field.field_type;
    this.size = field.size;
    this.value = value;
    this.disabled = disable;
}

VariationField.prototype.getInput = function() {
    this.id = this.name + this.number;
    var row, variation_value, variation_title, var_title_append;
    if (this.name == 'var_name') {
        variation_title = product_title;
        row = '<td><table>';
        for (var variation in variations.variationTypes) {
            if (variations.variationTypes[variation].used) {
                var variation_type_title = variations.variationTypes[variation].title;
                var variation_type_name = variations.variationTypes[variation].name;
                for (var variant in variations.variations) {
                    if (variations.variations[variant].details.var_name.value == this.value) {
                        variation_value = variations.variations[variant].details[variation_type_name].value;
                        var_title_append = variations.variations[variant].details.var_append.value;
                    }
                }

                row = row + '<tr><td>' + variation_type_title + ': </td><td>' + variation_value + '</td></tr>';
                variation_title = variation_title + ' {' + variation_value + '}';
            }
        }
        row = row + '</td></table>';
        variation_title = variation_title + ' ' + var_title_append;
        $('#var_setup').append('<tr class="hidden" ><td><input class="" name="' + this.id + '" value="' + variation_title + '" /></td></tr>');
    } else {
        row = '<td><input name=' + this.id + ' id=' + this.id + ' type=' + this.type + ' size=35 placeholder="' + this.title + '" class="' + this.name + '"value="';
        row = row + this.value + '"';
        if (this.disabled === true) {
            row = row + '" disabled';
        }
        if (this.type == 'checkbox') {
            if (this.value === true) {
                row = row + ' checked ';
            }
        }
        row = row + ' /></td>';
    }
    return row;
};

VariationField.prototype.updateValue = function() {
    var input = $('#' + this.id);

    if (this.type == 'checkbox') {
        if (input.is(':checked')) {
            this.value = true;
        } else {
            this.value = false;
        }
    } else if (input.val() !== null) {
        this.value = input.val();
    }
};



function TableRow(number, fields, value) {
    this.number = number;
    this.fields = fields;
    this.values = values;
    this.row = [];
}

Table.prototype.updateValues = function() {
    for (var variation in variations.variations) {
        for (var detail in variations.variations[variation].details) {
            variations.variations[variation].details[detail].updateValue();
        }
        variations.variations[variation].updateTitle();
    }
};

function Table(fields, values) {
    $('#var_setup_buttons').append('<td><input id="previous_page" value="<< Previous" type=submit name=previous /></td><td><input type=button value="Add" onclick="addRowsButton()" />&nbsp<input type=text size=2 name=more_var id=more_var_box />&nbspMore Variations<td><input id="next_page" value="Next >>" type=submit name=next /></td>');
    this.table = $('#var_setup');
    this.fields = fields;
    this.values = values;

    this.rows = [];

    var i = 0;

    for (var variation in this.values) {
        row = new TableRow(i, this.fields, this.values[variation]);
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
};

Table.prototype.write = function() {
    this.table.empty();
    this.resetRowNumbers();

    for (i=0; i<this.fields.length; i++) {
        this.table.append('<tr id=var_setup_row_' + this.fields[i].field_name + ' >');

        newRow = $('#var_setup_row_' + this.fields[i].field_name);
        var add_set_all = true;
        if (i > 0) {
            if (variations.variationTypes.hasOwnProperty([this.fields[i].field_name])) {
                if (variations.variationTypes[this.fields[i].field_name].used === true) {
                    add_set_all = false;
                } else {
                    add_set_all = true;
                }
            }
        } else {
            add_set_all = false;
        }

        if (add_set_all === true) {
            if (this.fields[i].field_type == 'checkbox') {
                var toggleButton = $('<input type=button id=toggle_' + this.fields[i].field_name + ' value="Toggle All" title="Toggles international shipping on or off for all variations."/>');
                toggleButton.click(toggleButtonGenerator(this.fields[i].field_name));
                var toggleField = $('<td>').append(toggleButton);
                newRow.append(toggleField);
            } else {
                var setAllButton = $('<input type=button title="Sets ' + this.fields[i].field_title + ' for every variation to match the left most." value="Set All" />');
                setAllButton.click(setAllButtonGenerator(this.fields[i].field_name));
                var buttonField = $('<td class="small_buton">').append(setAllButton);
                newRow.append(buttonField);
            }
        } else {
            newRow.append('<td class=small_button >');
        }
        newRow.append('<td title="' + this.fields[i].field_description + '" >' + this.fields[i].field_title + '</td>');
        for (var avariation in variations.variations) {
            var variation = variations.variations[avariation];
            if (variation.active) {
                for (var afield in variation.details) {
                    var field = variation.details[afield];
                    if (field.name == this.fields[i].field_name) {
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

    $('.variation_table').doubleScroll();


};

function resetTableGenerator() {
    return function() {
        table.updateValues();
        table.write();
    };
}

function toggleButtonGenerator(field_name) {
    return function(event){
        table.updateValues();
        var setTo = variations.variations[0].details[field_name].value;
        setTo = !setTo;
        for (var variation in variations.variations) {
            variations.variations[variation].details[field_name].value = setTo;
        }
        table.write();
    };
}

Table.prototype.resetRowNumbers = function() {
    for (i=0; i<this.rows.length; i++) {
        this.rows[i].number = i;
        for (x = 0; x<this.rows[i].row.length; x++) {
            this.rows[i].row[x].number = i;
        }
    }
};

Table.prototype.deleteRow = function(number) {
    if (this.rows.length > 2) {
        this.rows.splice(number, 1);
        this.write();
    }
};

Table.prototype.addRow = function() {
    this.rows.push(new TableRow('?', this.fields, ''));
};

function setAllButtonGenerator(field_name) {
    return function(event){
        table.updateValues();
        for (var variation in variations.variations) {
            variations.variations[variation].details[field_name].value = variations.variations[0].details[field_name].value;
        }
        table.write();
    };
}

Table.prototype.addRows = function(number) {
    for (i = 0; i < number; i++) {
        this.addRow();
    }
    this.write();
};

$('#var_form').submit(function(){
    if (variations_form_validate() === false) {
        window.scrollTo(0, 0);
        return false;
    }
    for(var varType in variations.variationTypes){
        $('#var_form').append('<input name="var_' + varType + '" value="' + variations.variationTypes[varType].used + '" class=hidden />');
    }
    $(':input').removeAttr('disabled');
    return true;
});
