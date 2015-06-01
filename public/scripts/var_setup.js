$(document).ready(function() {
    for (field in keyFields){
        var fieldName = keyFields[field]['field_name'];
        variationTypes[fieldName] = new VariationType(keyFields[field]['field_name'], keyFields[field]['field_title']);
    }
    write();
});

function write(){
    $('#add_variations tr').remove();
    $('#add_variation_types tr').remove();
    $('#list_of_variables tr').remove();
    $('#list_of_variations tr').remove();
    
    addAddVariationTypes();
    addAddVariations();
    addVariationList();
    
}

function addVariationButton(button){
    var field = button.id;
    field = field.replace('add_variations_to_', '');
    field = field.replace('_button', '');
    var inputField = $('#add_to_' + field);
    var rawInput = inputField.val();
    rawInput = rawInput.replace(', ', ',');
    var variations = rawInput.split(',');
    for (i in variations) {
        addVariation(field, variations[i]);
    }
    write();
}

function addVariation(field, variation) {
    variationTypes[field].variations.push(variation);
}

function addAddVariationTypes(){
    for (field in variationTypes){
        if (variationTypes[field].used === false){
            var id = 'add_var_type_' + variationTypes[field].name;
            var name = variationTypes[field].title;
            
            $('#add_variation_types').append('<tr>');
            var newRow = $('#add_variation_types tr:last');
            newRow.append('<td class="add_to_label"><label for=' + id + '>' + name + '</label></td>');
            newRow.append('<td class="add_to_button"><input type=button value="Add Variation Type" id=add_' + field + '_button /></td>');
            
            $('#add_' + field + '_button').click(function(){
                addVariationTypeButton(this);
            });
        }
    }
    
    add_instructions('add_variation_types', 'Click "Add Variation Type" to add variation types to the product.');
}

function addAddVariations(){
    for (field in variationTypes){
        if (variationTypes[field].used === true){
            var id = 'add_to_' + variationTypes[field].name;
            var name = variationTypes[field].title + 's';
            
            $('#add_variations').append('<tr>');
            var newRow = $('#add_variations tr:last');
            newRow.append('<td class="add_to_label"><label for=' + id + '>Add ' + name + ': </label></td>');
            newRow.append('<td class="add_to_input"><input size=75 name=' + id + ' id=' + id + ' /></td>');
            newRow.append('<td class="add_variations_to_button"><input type=button value=Add id=add_variations_to_' + field + '_button /></td>');
            newRow.append('<td class=remove_variation_type ><input type=button value=Remove id=remove_variation_type_' + field + ' </td>');
            
            $('#add_variations').append('<tr><td colspan=4 >');
            $('#add_variations tr:last td').append('<table id=list_of_variants_' + field + ' >');
            for (variant in variationTypes[field].variations){
                $('#list_of_variants_' + field).append('<td><div class=variation_box ><span id=variation_no_' + variant + ' class=variation_of_' + field + ' >' + variationTypes[field].variations[variant] + ' </span><span class=remove_x id=remove_variation_no_' + variant + '_from_' + field + ' >x</span></div>');
                $('#remove_variation_no_' + variant + '_from_' + field).click(function(){
                    removeVariationButton(this);
                });
                
                variant += 1;
            }
            
            
            
            $('#add_variations_to_' + field + '_button').click(function(){
                addVariationButton(this);
            });
            
            $('#remove_variation_type_' + field).click(function(){
                removeVariationTypeButton(this);
            });
        }
    }
    
    add_instructions('add_variations', '<p>List all variations for each variation type, separated by commas. More can be added later if required.</p><p>For example: Green, Red, Blue.</p><p>Click "Add" to add the variations or click "Remove" to remove the variation type.</p>');
}

function addVariationList(){
    var i = 1;
    var varlist = [];
    for (field in variationTypes){
        if (variationTypes[field].used === true){
            if (variationTypes[field].variations.length > 0) {
                if (varlist.length == 0) {
                    varlist = variationTypes[field].variations;
                } else {
                    var oldVarList = varlist;
                    var newVarList = [];
                    for (vari in oldVarList){
                        for (othervari in variationTypes[field].variations){
                            newVarList.push(oldVarList[vari] + ' ' + variationTypes[field].variations[othervari]);
                        }
                    }
                    varlist = newVarList;
                }
            }
        }
    }
    
    console.log(varlist);
    
    for (variation in varlist){
        variations.push(new Variation(varlist[variation]));
    }
    
    $('#list_of_variations').html('');
    for (varient in varlist) {
        $('#list_of_variations').append('<tr>');
        var newRow = $('#list_of_variations tr:last');
        newRow.append('<td>' + varlist[varient] + '</td>');
        var button = '<td><input id=toggle_variation_' + varlist[varient] + ' class=toggle_variation_button type=button ';
        var variation = get_variation(varlist[varient]);
        if (variation.enabled === true) {
            button = button + 'value=Remove '
        } else {
            button = button + 'value=Re-Add '
        }
        button = button + ' />';
        newRow.append($(button));
    }
}

function get_variation(variation){
    for (varient in variations){
        if (variations[varient].name === variation) {
            return variations[varient];
        }
    }
    return false;
}

function removeVariationButton(button){
    var variationNumber = button.id.substring(20, 21);
    console.log(variationNumber);
    var field = button.id.substring(27);
    console.log(field);
    field = field.replace('variation_of_', '');
    removeVariation(field, variationNumber);
    write();
}

function removeVariation(field, variationNumber) {
    variationTypes[field].variations.splice(variationNumber, 1);
}

function add_instructions(table, text){
    $('#' + table + ' tr:first').append('<td rowspan=' + $('#' + table + ' tr').length + ' >' + text);
}

function addVariationTypeButton(button){
    var field = button.id.replace('add_', '');
    field = field.replace('_button', '');
    addVariationType(field);
}

function addVariationType(field) {
    variationTypes[field].used = true;
    write();
}

function removeVariationTypeButton(button){
    var field = button.id.replace('remove_variation_type_', '');
    removeVariationType(field);
}

function removeVariationType(field) {
    variationTypes[field].used = false;
    write();
}

function capitalizeFirstLetter(string) {
    string[0] = string[0].toUpperCase();
    return string;
}

variationTypes = {};
variations = [];

function VariationType(name, title){
    this.used = false;
    this.name = name;
    this.title = title;
    this.variations = [];
}

function Variation(name) {
    this.name = name;
    this.enabled = true;
}