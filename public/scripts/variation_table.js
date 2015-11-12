$(document).ready(function() {
    $('.set_all').each(function() {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[1];
        $(this).click(set_all_generator(field));
    });
    $('.toggle_all').each(function() {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[1];
        $(this).click(toggle_all_generator(field));
    });
    resetColumns();
    var inputs = $('#var_setup input').not(':input[type=button]');
    inputs.each(function () {
        var id = $(this).attr('id');
        var idList = id.split('-');
        var number = idList[1];
        $(this).focus(inputFocusGenerator(number));
    });
});

$('#var_form').submit(function() {
    var $hidden = $("<input type='hidden' name='variation_details'/>");
    $hidden.val(JSON.stringify(getVariationDetails()));
    $(this).append($hidden);
    return true;
});

$('input').blur(function() {
    resetColumns();
});

function getVariationDetails() {
    variationDetails = {};
    for (i=0;i<variation_count;i++) {
        variationDetails[i] = {};
        for (x=0; x<fields.length; x++) {
            variationDetails[i][fields[x].field_name] = '';
        }
    }
    var inputs = $('#var_setup input').not(':input[type=button]');
    inputs.each(function () {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[0];
        var number = id_array[1];
        var value = $(this).val();
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
        var inputs = $('#var_setup input').not(':input[type=button]');
        inputs.each(function () {
            var id = $(this).attr('id');
            var id_array = id.split('-');
            var input_field = id_array[0];
            if (input_field == field) {
                $(this).val(new_value);
            }
        });
        table_adjust();
    };
}

function inputFocusGenerator(variation_number) {
    return function(event) {
        var new_size = getMaxValLen(variation_number);
        if (new_size > default_col_size) {
            resizeColumn(variation_number, new_size);
        }
    };
}

function resizeColumn(variationNumber, size) {
    var inputs = $('#var_setup input').not(':input[type=button]');
    inputs.each(function () {
        var id = $(this).attr('id');
        var idList = id.split('-');
        var number = idList[1];
        if (number == variationNumber) {
            $(this).attr('size', size);
        }
    });
}

function resetColumns() {
    for (i=0;i<variation_count;i++) {
        resizeColumn(i, default_col_size);
    }
}

function getMaxValLen(variationNumber) {
    maxLenght = 0;
    var inputs = $('#var_setup input').not(':input[type=button]');
    inputs.each(function () {
        var id = $(this).attr('id');
        var idList = id.split('-');
        var number = idList[1];
        var value = $(this).val();
        if (number == variationNumber) {
            if (typeof value != "undefined"){
                if (value.length > maxLenght) {
                    maxLenght = value.length;
                    console.log(value);
                    console.log(maxLenght);
                }
            }
        }
    });
    console.log(maxLenght);
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
        console.log(checked);
        var inputs = $('#var_setup input').not(':input[type=button]');
        inputs.each(function () {
            var id = $(this).attr('id');
            var id_array = id.split('-');
            var input_field = id_array[0];
            if (input_field == field) {
                if (checked) {
                    $(this).prop('checked', false);
                } else {
                    $(this).prop('checked', true);
                }
            }
        });
        table_adjust();
    };
}
