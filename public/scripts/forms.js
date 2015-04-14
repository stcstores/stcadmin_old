function setFormStyle(){

    $('input').focus(function() {
        $(this).css('border-color', '#729292');
    });
    
    $('input').blur(function() {
        $(this).removeAttr('style');
    });
    
    $('select').focus(function() {
        $(this).css('border-color', '#729292');
    });
    
    $('select').blur(function() {
        $(this).removeAttr('style');
    });
    
    $('textarea').focus(function() {
        $(this).css('border-color', '#729292');
    });
    
    $('textarea').blur(function() {
        $(this).removeAttr('style');
    });
    
    $('#content').find($('form')).find($('input, textarea, checkbox, select')).each(function (){
    if (($(this).attr('type') == 'button')){
    } else if ($(this).attr('type') == 'submit') {
    } else {
        validate($(this));
    }
    
});

}

illegalChars = ['"', "'", '~', ';', '<', '>', '\\', '/'];

setFormStyle();

$('#content').find($('form')).find($('input, textarea, checkbox, select')).each(function (){
    if (($(this).attr('type') == 'button')){
    } else if ($(this).attr('type') == 'submit') {
    } else {
        validate($(this));
    }
    
});

function validate(input) {
    var inputId = input.attr('id');
    
    if (inputId === 'item_title') {
        itemTitleValidate(input);
    }
    
    var priceFields = [/^retail_price\d*$/i, /^purchase_price\d*$/i, /^ebay_price\d*$/i, /^am_price\d*$/i];    
    for (key in priceFields) {
        if (priceFields[key].test(inputId)) {
            priceValidate(input);
        }
    }
    
    if (inputId === 'short_description') {
        shortDescriptionValidate(input);
    }
}

function priceValidate(input) {
    var value = input.val();
    input.blur(function() {
        if (value === '') {
            writeError(input, 'Please add price');
        }
    });
    
    clearError(input);
}

function itemTitleValidate(input) {
    input.blur(function() {
        var value = input.val();
        for (key in illegalChars) {
            value = value.replace(illegalChars[key], '');
        }
        input.val(value);
        
        if (input.val().length == 0) { // if empty
            writeError(input, 'Product title is required');
        } else if (input.val().length < 6) { // if title less than 5 chars
            writeError(input, 'Title must be at least 5 Characters');
        } else if (input.val().length > 50) { // if title more than 50 chars
            writeError(input, 'Title must not exceed 50 Characters');
        }
        
    });
    
    clearError(input);
}

function shortDescriptionValidate(input) {
    
    input.blur(function() {
        var value = input.val();
        for (key in illegalChars) {
            value = value.replace(illegalChars[key], '');
        }
        input.val(value);
        
        if (input.val().length == 0) { // if empty
            writeError(input, 'Product description is required');
        } else if (input.val().length < 26) { // if title less than 5 chars
            writeError(input, 'Description must be at least 25 Characters');
        }
    });
    
    clearError(input);
}

function writeError(input, text) {
    input.addClass('error');
    var written = false;
    input.parent().find('p').each(function() {
        if ($(this).text() == text) {
            written = true;
        }
    });
    
    if (written === true) {
        return '';
    }
    input.after('<p class=error>' + text + '</p>');
}

function clearError(input) {
    input.focus(function() {
        input.parent().find('p').each(function() {
            $(this).remove();
        });
        input.removeClass('error');
    });
}