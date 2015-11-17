inputs = $('#var_setup input').not(':input[type=button]');
errors = [];

$(document).ready(function() {
    resetColumns();
    setSetAll();
    setToggleAll();
    setInputFocus();
    setArrowKeyNav();
    $('#reset_variations').click(function(){
        window.location.href='new_linnworks_product_var_setup.php';
    });
});

function setArrowKeyNav() {
    inputs.keyup(function(e) {
        if (e.keyCode == 38) {
            arrowKeyNavigate(this, 'up');
        }
        else if (e.keyCode == 37) {
            arrowKeyNavigate(this, 'left');
        }
        else if (e.keyCode == 39) {
            arrowKeyNavigate(this, 'right');
        }
        else if (e.keyCode == 40) {
            arrowKeyNavigate(this, 'down');
        }
    });
}

function arrowKeyNavigate(input, direction) {
    var field_number, new_field_number;
    var new_field, new_number;
    var inputDetails = getInputDetails(input);
    var number = inputDetails.number;
    for (var i=0; i<fields.length; i++) {
        if (fields[i].field_name == inputDetails.field) {
            field_number = i;
            break;
        }
    }
    if (direction == 'left') {
        new_number = number - 1;
        new_field_number = field_number;
    } else if (direction == 'right') {
        new_number = number + 1;
        new_field_number = field_number;
    } else if (direction == 'up') {
        new_number = number;
        new_field_number = field_number - 1;
    } else if (direction == 'down') {
        new_number = number;
        new_field_number = field_number + 1;
    }
    if (new_number < 0) {
        new_number = variation_count - 1;
    } else if (new_number > variation_count - 1) {
        new_number = 0;
    }
    if (new_field_number < 0) {
        new_field_number = fields.length - 1;
    } else if (new_field_number > fields.length - 1) {
        new_field_number = 0;
    }
    new_field = fields[new_field_number].field_name;
    newInput = getInput(new_field, new_number);
    if (newInput.attr('disabled')) {
        arrowKeyNavigate(newInput, direction);
    } else {
        newInput.focus();
    }
}

function setSetAll() {
    $('.set_all').each(function() {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[1];
        $(this).click(set_all_generator(field));
    });
}

function setToggleAll() {
    $('.toggle_all').each(function() {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[1];
        $(this).click(toggle_all_generator(field));
    });
}

function setInputFocus() {
    var inputs = $('#var_setup input').not(':input[type=button]');
    inputs.each(function () {
        var inputDetails = getInputDetails(this);
        $(this).focus(inputFocusGenerator(inputDetails.number));
        $(this).blur(inputBlurGenerator());
    });
}

$('#var_form').submit(function(e) {
    var $hidden = $("<input type='hidden' name='variation_details'/>");
    var formData = getVariationDetails();
    if (validateFormData(formData)) {
        $hidden.val(JSON.stringify(formData));
        $(this).append($hidden);
        return true;
    } else {
        e.preventDefault();
        return false;
    }
});

function getVariationDetails() {
    variationDetails = [];
    for (var i=0;i<variation_count;i++) {
        variationDetails[i] = {};
        for (var x=0; x<fields.length; x++) {
            variationDetails[i][fields[x].field_name] = '';
        }
    }
    inputs.each(function () {
        var inputDetails = getInputDetails(this);
        var value = inputDetails.val,
            field = inputDetails.field,
            number = inputDetails.number;
        if (typeof value == "undefined") {
            value = '';
        } else if ($(this).attr('type') == 'checkbox') {
            if ($(this).prop('checked')) {
                value = true;
            } else {
                value = false;
            }
        }
        variationDetails[number][field] = value;
    });
    return variationDetails;
}

function set_all_generator(field) {
    return function(event) {
        var new_value = $('#' + field + '-0').val();
        if (typeof new_value == "undefined") {
            new_value = '';
        }
        for (var i=0; i<variation_count; i++){
            $('#' + field + '-' + i).val(new_value);
        }
        getInput(field, 0).focus();
    };
}

function inputFocusGenerator(variation_number) {
    return function(event) {
        expandColumn(variation_number);
    };
}

function inputBlurGenerator() {
    return function(event) {
        resetColumns();
    };
}

function resizeColumn(variationNumber, size) {
    var inputs = $('#var_setup input').not(':input[type=button]');
    for (var i=0; i<fields.length; i++) {
        $('#' + fields[i].field_name + '-' + variationNumber).attr('size', size);
    }
}

function resetColumns() {
    for (var i=0;i<variation_count;i++) {
        resizeColumn(i, default_col_size);
    }
}

function expandColumn(variationNumber) {
    var newSize = getMaxValLen(variationNumber);
    if (newSize > default_col_size) {
        resizeColumn(variationNumber, newSize);
    }
}

function getMaxValLen(variationNumber) {
    var maxLenght = 0;
    var inputs = $('#var_setup input').not(':input[type=button]');
    for (var i=0; i<fields.length; i++){
        var value = $('#' + fields[i].field_name + '-' + variationNumber).val();
        if (typeof value != "undefined"){
            if (value.length > maxLenght) {
                maxLenght = value.length;
            }
        }
    }
    return maxLenght;
}

function toggle_all_generator(field) {
    return function(event) {
        var checked;
        if ($('#' + field + '-0').prop('checked')) {
            checked = true;
        } else {
            checked = false;
        }
        for (var i=0; i<variation_count; i++) {
            var input = $('#' + field + '-' + i);
            if (checked) {
                input.prop('checked', false);
            } else {
                input.prop('checked', true);
            }
        }
    };
}

function getInputDetails(input) {
    var inputDetails = {},
        id = $(input).attr('id'),
        idList = id.split('-');
    inputDetails.field = idList[0];
    inputDetails.number = parseInt(idList[1]);
    inputDetails.val = $(input).val();
    return inputDetails;
}

function getInput(field, number) {
    return $('#' + field + '-' + number);
}
