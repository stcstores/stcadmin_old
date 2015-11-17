function validateFormData(formData) {
    //console.log('Validating');
    inputs.attr('class', '');
    $('#errors').html('');
    regexTest(formData, 'barcode', 'barcode');
    noneEmpty(formData, 'retail_price');
    regexTest(formData, 'retail_price', 'price');
    noneEmpty(formData, 'purchase_price');
    regexTest(formData, 'purchase_price', 'price');
    noneEmpty(formData, 'shipping_price');
    regexTest(formData, 'shipping_price', 'price');
    noneEmpty(formData, 'weight');
    onlyNumeric(formData, 'weight');
    onlyNumeric(formData, 'width');
    onlyNumeric(formData, 'depth');
    onlyNumeric(formData, 'height');
    for (i=0; i<errors.length; i++) {
        $('#errors').append('<p class="error">' + errors[i]);
    }
    //console.log(errors.length + ' errors');
    if (errors.length === 0){
        console.log('Form Send');
        return true;
    } else {
        errors = [];
        return false;
    }
}

function getFieldValues(formData, field) {
    var values = [];
    for (var i=0; i<variation_count; i++) {
        values.push(formData[i][field]);
    }
    return values;
}

function regexTest(formData, field, regex) {
    if (regex == 'price') {
        expression = /^([0-9]*((.)[0-9]{0,2}))$/;
    } else if (regex == 'barcode') {
        expression = /^\d{12,13}$/;
    }
    var data = getFieldValues(formData, field);
    var error = true;
    for (var i=0; i<data.length; i++) {
        if ((!(isEmpty(data[i]))) && (!(expression.test(data[i])))) {
            error = false;
            var input = getInput(field, i);
            addError(input, getFieldTitle(field) + " must be valid " + regex);
        }
    }
    return error;
}

function noneEmpty(formData, field) {
    //console.log('Testing None Empty: ' + field);
    var error = true;
    var data = getFieldValues(formData, field);
    for (var i=0; i<data.length; i++) {
        if (isEmpty(data[i])) {
            var input = getInput(field, i);
            error = false;
            addError(input, "All variations require " + getFieldTitle(field));
        }
    }
    return error;
}

function onlyNumeric(formData, field) {
    //console.log('Testing only numeric: ' + field);
    var error = true;
    var data = getFieldValues(formData, field);
    for (var i=0; i<data.length; i++) {
        if ((!(isEmpty(data[i]))) && (!(isNumeric(data[i])))) {
            var input = getInput(field, i);
            error = false;
            addError(input, "All " + getFieldTitle(field) + " must be numbers");
        }
    }
    return error;
}

function isNumeric(value) {
  return !isNaN(parseFloat(value)) && isFinite(value);
}

function unique_required(formData, field) {
    var i;
    var data = getFieldValues(formData, field);
    var error = true;
    var input;
    for (i=0; i<variation_count; i++) {
        if (isEmpty(data[i])) {
            error = false;
            input = getInput(field, i);
            addError(input, field + ' is required');
        }
    }
    if (data.length !== arrayUnique(data).length) {
        for (i=0; i<variation_count; i++) {
            input = getInput(field, i);
            addError(input, 'All ' + getFieldTitle(field) + ' values must be unique');
        }
        error = false;
    }
    return error;
}

function unique_unRequired(formData, field) {
    var i;
    var input;
    var data = getFieldValues(formData, field);
    if (!(isEmpty(data[0]))) {
        if (data.length === arrayUnique(data).length) {
            return true;
        } else {
            for (i=0; i<variation_count; i++) {
                input = getInput(field, i);
                addError(input, 'All ' + getFieldTitle(field) + ' values must be unique');
            }
        }
    } else {
        var error = false;
        for (i=0; i<variation_count; i++) {
            if (!(isEmpty(data[i]))) {
                error = true;
                input = getInput(field, i);
                addError(input, 'If any ' + getFieldTitle(field) + ' are filled they all must be');
            }
        }
    }
}

var arrayUnique = function(a) {
    return a.reduce(function(p, c) {
        if (p.indexOf(c) < 0) p.push(c);
        return p;
    }, []);
};

function addError(input, message) {
    console.log(message);
    $(input).attr('class', 'error');
    if (errors.indexOf(message) == -1) {
        //console.log('Error: ' + message);
        errors.push(message);
    }
}

function isEmpty(value) {
    if (value === undefined) {
        return true;
    } else if (value === '') {
        return true;
    }
    return false;
}

function getFieldTitle(fieldName) {
    var fieldTitle;
    for (var i=0; i<fields.length; i++) {
        if (fields[i].field_name == fieldName) {
            fieldTitle = fields[i].field_title;
        }
    }
    return fieldTitle;
}
