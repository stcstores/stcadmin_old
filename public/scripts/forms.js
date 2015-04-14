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
    var priceFields = [/^retail_price\d*$/i, /^purchase_price\d*$/i, /^ebay_price\d*$/i, /^amazon_price\d*$/i];
    
    for (key in priceFields) {
        if (priceFields[key].test(inputId)) {
            priceValidate(input);
        }
    }
}

function priceValidate(input) {
    input.blur(function() {
        if (input.val() === '') {
            input.after(writeError(input, 'Please add price'));
            input.addClass('error');
        }
    });
    
    input.focus(function() {
        input.parent().find('p').each(function() {
            $(this).remove();
        });
        input.removeClass('error');
    });
}

function writeError(input, text) {
    var written = false;
    input.parent().find('p').each(function() {
        if ($(this).text() == text) {
            written = true;
        }
    });
    
    if (written === true) {
        return '';
    }
    return '<p class=error>' + text + '</p>';
}