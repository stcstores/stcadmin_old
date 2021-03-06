function Variation(attributes) {
    this.attributes = attributes;
    this.active = true;
}

function VariationType(name, title){
    this.used = false;
    this.name = name;
    this.title = title;
    this.variations = [];
}

function Variations() {
    this.variationTypes = {};
    for (var field in keyFields){
        var fieldName = keyFields[field].field_name;
        this.variationTypes[fieldName] = new VariationType(keyFields[field].field_name, keyFields[field].field_title);
    }

    this.variations = [];
}

Variations.prototype.getVariationTypes = function() {
    var variationTypes = [];
    for (i = 0;i < keyFields.length;i++) {
        var field = keyFields[i].field_name;
        if (this.variationTypes[field].used) {
            variationTypes.push(field);
        }
    }
    return variationTypes;
};

Variations.prototype.getVariationList = function() {
    var variationList = [];
    for (i=0; i < this.variations.length; i++) {
        var variation = this.variations[i];
        if (variation.active) {
            variationList.push(variation.attributes);
        }
    }
    return variationList;
};

Variations.prototype.variation_exists = function(variation_dictionary) {
    var var_exists = true;
    for (var varType in variation_dictionary) {
        if (!($.inArray(variation_dictionary[varType], this.variationTypes[varType].variations))) {
            var_exists = false;
        }
    }
    return var_exists;
};

Variations.prototype.get_variations = function() {
    var varlists = [];
    var vartypes = [];

    for (var field in variations.variationTypes) {
        if (variations.variationTypes[field].used === true) {
            varlists.push(variations.variationTypes[field].variations);
            vartypes.push(variations.variationTypes[field].name);
        }
    }

    var all_variations = get_valid_combinations(varlists);

    var all_variation_dicts = [];

    for (var varlist in all_variations){
        var newVarDict = {};
        for (i = 0; i < vartypes.length; i++) {
            newVarDict[vartypes[i]] = all_variations[varlist][i];
        }
        all_variation_dicts.push(newVarDict);
    }

    return all_variation_dicts;
};

Variations.prototype.update_variations = function() {
    new_variations = this.get_variations();
    for (var variation in this.variations) {
        var var_exisits = false;
        for (var new_var in new_variations) {
            if (variation_var_dict_compare(this.variations[variation], new_variations[new_var])) {
                var_exisits = true;
            }
        }
        if (!(var_exisits)) {
            this.variations.splice(variation, 1);
        }
    }

    for (var new_variation in new_variations) {
        var variation_exsits = false;
        for (variation in this.variations){
            if (variation_var_dict_compare(this.variations[variation], new_variations[new_variation])) {
                variation_exsits = true;
            }
        }
        if (!(variation_exsits)) {
            this.variations.push(new Variation(new_variations[new_variation]));
        }
    }
};

function variation_var_dict_compare(variation, var_dict) {
    var match = true;

    for (var attr in variation.attributes) {
        if (!(var_dict[attr])) {
            match = false;
        } else {
            if (variation.attributes[attr] !== var_dict[attr]) {
                match = false;
            }
        }
    }

    for (attr in var_dict) {
        if (!(variation.attributes[attr])) {
            match = false;
        } else {
            if (var_dict[attr] !== variation.attributes[attr]) {
                match = false;
            }
        }
    }
    return match;
}

function get_valid_combinations(arg) {
    function helper(arr, i) {
        for (var j = 0, l = arg[i].length; j < l; j++) {
            var a = arr.slice(0); // clone arr
            a.push(arg[i][j]);
            if (i == max) {
                r.push(a);
            } else helper(a, i + 1);
        }
    }

    if (arg.length > 0) {
        var r = [],
            //arg = arguments,
            max = arg.length - 1;
        helper([], 0);
        return r;
    } else {
        return [];
    }
}

function addAddVariationTypes(){
    for (var field in variations.variationTypes){
        var varType = variations.variationTypes[field];
        if (varType.used === false){
            var id = 'add_var_type_' + varType.name;
            var name = varType.title;

            $('#add_variation_types').append('<tr>');
            var newRow = $('#add_variation_types tr:last');
            newRow.append('<td class="add_to_label"><label for=' + id + '>' + name + '</label></td>');
            newRow.append('<td class="add_to_button"><input type=button value="Add Variation Type" id=add_' + field + '_button /></td>');

            $('#add_' + field + '_button').click(toggle_variation_type_used_generator(varType));
        }
    }

    add_instructions('add_variation_types', 'Click "Add Variation Type" to add variation types to the product.');
}

function addAddVariations(){
    for (var field in variations.variationTypes){
        var varType = variations.variationTypes[field];
        if (varType.used === true){
            var id = 'add_to_' + varType.name;
            var name = varType.title + 's';
            $('#add_variations').append('<tr>');
            var newRow = $('#add_variations tr:last');
            newRow.append('<td class="add_to_label"><label for=' + id + '>Add ' + name + ': </label></td>');
            newRow.append('<td class="add_to_input"><input style="width: 100%;" name=' + id + ' id=' + id + ' /></td>');
            newRow.append('<td class="add_variations_to_button"><input type=button value=Add id=add_variations_to_' + field + '_button /></td>');
            newRow.append('<td class=remove_variation_type ><input type=button value=Remove id=remove_variation_type_' + field + ' </td>');
            $('#add_variations').append('<tr><td colspan=4 >');
            $('#add_variations tr:last td').append('<table id=list_of_variants_' + field + ' >');
            for (var variant in varType.variations){
                $('#list_of_variants_' + field).append('<td><div class=variation_box ><span id=variation_no_' + variant + ' class=variation_of_' + field + ' >' + varType.variations[variant] + ' </span><span class=remove_x id=remove_variation_no_' + variant + '_from_' + field + ' >x</span></div>');
                $('#remove_variation_no_' + variant + '_from_' + field).click(removeVariationGenerator(varType, variant));

                variant += 1;
            }
            $('#add_variations_to_' + field + '_button').click(addVariationGenerator(field));
            $('#add_to_' + field).blur(addVariationGenerator(field));
            $('#remove_variation_type_' + field).click(toggle_variation_type_used_generator(varType));
        }
    }

    add_instructions('add_variations', '<p>List all variations for each variation type, separated by commas. More can be added later if required.</p><p>For example: Green, Red, Blue.</p><p>Click "Add" to add the variations or click "Remove" to remove the variation type.</p>');
}

function addVariationList() {
    variations.update_variations();
    set_variation_numbers();
    var header = '<tr>';
    for (var varType in variations.variationTypes) {
        if (variations.variationTypes[varType].used) {
            var type = variations.variationTypes[varType].title;
            header = header + '<th>' + type + '</th>';
        }
    }
    header = header + '<th></th>';
    $('#list_of_variations').append($(header));
    var new_row;
    for (var variation in variations.variations) {
        if (variations.variations[variation].active) {
            new_row = '<tr>';
        } else {
            new_row = '<tr class="varient_disabled">';
        }
        for (varType in variations.variationTypes) {
            if (variations.variationTypes[varType].used) {
                var variationType = variations.variationTypes[varType].name;
                var attributes = variations.variations[variation].attributes;
                new_row = new_row + '<td>' + attributes[variationType] + '</td>';
            }
        }
        new_row = new_row + '<td>';
        $('#list_of_variations').append($(new_row));
        var button = $('<input type=button>');
        if (variations.variations[variation].active) {
            button.val('Remove');
        } else {
            button.val('Re-Add');
        }
        button.click(remove_varient_generator(variations.variations[variation]));
        $('#list_of_variations tr:last td:last').append(button);
    }
}

function addVariation(field, variation) {
    variations.variationTypes[field].variations.push(variation);
}

function toggle_variation_type_used_generator(varType) {
    return function(event) {
        varType.used = !varType.used;
        variations.variations = [];
        write();
    };
}

function addVariationGenerator(field) {
    return function(event) {
        var inputField = $('#add_to_' + field);
        var rawInput = inputField.val();
        if (rawInput) {
            rawInput = rawInput.replace(', ', ',');
            var variations = rawInput.split(',');
            for (i=0; i<variations.length; i++) {
                addVariation(field, variations[i].trim());
            }
            write();
        }
    };
}

function removeVariationGenerator(varType, variation){
    return function(event){
        varType.variations.splice(variation, 1);
        write();
    };
}

function remove_varient_generator(variation) {
    return function(event){
        variation.active = !variation.active;
        write();
    };
}

function add_instructions(table, text){
    $('#' + table + ' tr:first').append('<td rowspan=' + $('#' + table + ' tr').length + ' >' + text);
}

function set_variation_numbers() {
    var varNumber = 0;
    if (variations.variations.length > 0) {
        for (var i=0; i < variations.variations.length; i++) {
            if (variations.variations[i].active === true) {
                for (var detail in variations.variations[i].details) {
                    variations.variations[i].details[detail].number = varNumber;
                }
                varNumber ++;
            }
        }
    }
}

function write() {
    $('#add_variations tr').remove();
    $('#add_variation_types tr').remove();
    $('#list_of_variables tr').remove();
    $('#list_of_variations tr').remove();
    $('#var_setup tr').remove();
    addAddVariationTypes();
    addAddVariations();
    addVariationList();
}

$('#var_form').submit(function() {
    var $hidden = $("<input type='hidden' name='variation_types'/>");
    var $hidden2 = $("<input type='hidden' name='variations'/>");
    var variationTypes = variations.getVariationTypes();
    var variationList = variations.getVariationList();
    if (variationList.length > 1) {
        $hidden.val(JSON.stringify(variationTypes));
        $hidden2.val(JSON.stringify(variationList));
        $(this).append($hidden);
        $(this).append($hidden2);
        return true;
    } else {
        $('#errors').html('');
        $('#errors').append('<p class=error >There must be at least two variations');
        return false;
    }
});

$(document).ready(function() {
    variations = new Variations();
    write();
});
