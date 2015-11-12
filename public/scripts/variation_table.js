function table_adjust() {
    var inputs = $('#var_setup input').not(':input[type=button]');
    var sizes = [];
    for (i=0; i<variation_count; i++) {
        sizes.push(9);
    }
    $.each(fields, function(index, field){
        sizes[field.field_name] = field.size;
    });
    inputs.each(function () {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[0];
        var number = id_array[1];
        var input = $(this).val();
        if (typeof input !== "undefined") {
            if (input.length > sizes[number]){
                sizes[number] = input.length;
            }
        }
    });
    inputs.each(function () {
        var id = $(this).attr('id');
        var id_array = id.split('-');
        var field = id_array[0];
        var number = id_array[1];
        $(this).attr('size', sizes[number]);
    });
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
    table_adjust();
});

$('input').blur(function() {
    table_adjust();
});
