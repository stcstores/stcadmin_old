function variations_form_validate() {
    var form_valid = true;
    
    $('#var_error').attr('class', 'hidden');
    $('#var_error').empty();
    $('.error').attr('class', '');
    
    if (!(check_unique('var_name'))) {
        add_error('Variation titles must be unique. Check your key fields.');
        form_valid = false;
    }
    
    if (!(priceCheck('Purchase Price', 'purchase_price'))) {
        form_valid = false;
    }
    
    if (!(priceCheck('Retail Price', 'retail_price'))) {
        form_valid = false;
    }
    
    if (!(priceCheck('Shipping Price', 'shipping_price'))) {
        form_valid = false;
    }
    
    if (!(all_empty('var_append'))) {
        if (!(check_unique('Title Appendix', 'var_append'))) {
            form_valid = false;
        }
    }
    
    if ((!(all_empty('barcode'))) && (!(none_empty('barcode')))) {
        objects = getObjects('barcode');
        objects.attr('css', 'error');
        add_error('If any variation has a barcode they all must');
    }
    
    if (!(all_empty('barcode'))) {
        if (!(barcodeCheck('Barcode', 'barcode'))) {
            form_valid = false;
        }
    }
    
    var keyFieldsUnique = true;
    var keyFieldsComplete = true;
    
    for (field in keyFields) {
        
        if (keyFields[field] === true) {
            if (!(none_empty(field))) {
                keyFieldsComplete = false;
            }
            if (!(check_unique(field))) {
                keyFieldsUnique = false;
            }
        }
    }
    
    if (!(keyFieldsComplete)) {
        add_error('All key fields must be filled.');
        form_valid = false;
    }
    
    if (!(keyFieldsUnique)) {
        add_error('All key fields must be unique.');
        form_valid = false;
    }
    
    console.log(form_valid);
    return form_valid;
}

function check_unique(field){
    objects = getObjects(field);
    var values = objects.map(function(){
        var value = $(this).val()
        return value;
    }).get();
    
    values = values.sort()
    
    for (var i = 0; i < values.length - 1; i++) {
        if (values[i + 1] == values[i]) {
            objects.each(function() {
                this.attr('class', 'error')
            });
            
            return false;
        }
    }
    return true;
}

function none_empty(field) {
    objects = getObjects(field);
    var values = objects.map(function(){
        var value = $(this).val()
        return value;
    }).get();
    
    var emptyFields = false;
    
    for (index in values) {
        if (values[index] === '') {
            emptyFields = true;
        }
    }
    
    if (emptyFields) {
        return false
    }
    return true;
}

function priceCheck(name, field){
    objects = getObjects(field);
    var values = objects.map(function(){
        var value = $(this).val()
        return value;
    }).get();
    
    var validPrices = true;
    
    for (value in values) {
        if (!(priceRegEx.test(values[value]))) {
            $('#' + field + value).attr('class', 'error');
            validPrices = false;
        }
    }
    
    if (!(validPrices)) {
        add_error(name + ' must be a valid price');
        return false;
    }
    return true;
}

function barcodeCheck(name, field){
    objects = getObjects(field);
    var values = objects.map(function(){
        var value = $(this).val()
        return value;
    }).get();
    
    var validBarcodes = true;
    
    for (value in values) {
        if (!(barcodeRegEx.test(values[value]))) {
            $('#' + field + value).attr('class', 'error');
            validBarcodes = false;
        }
    }
    
    if (!(validBarcodes)) {
        add_error(name + ' must be a valid Barcode');
        return false;
    }
    return true;
}

function add_error(errorText) {
    $('#var_error').attr('class', 'pagebox');
    $('#var_error').width(1000);
    $('#var_error').css('margin-bottom', '10px');
    $('#var_error').append('<p class=error >' + errorText);
}

function all_empty(field){
    objects = getObjects(field);
    var values = objects.map(function(){
        var value = $(this).val()
        return value;
    }).get();
    
    for (index in values) {
        if (!(values[index] === '')) {
            return false;
        }
    }
    return true;
}

function getObjects(field_name){
    var objects = []
    for(i=0; i < table.varCount(); i++) {
        objects.push(($('#' + field_name + i)));
    }
    return $(objects);
}