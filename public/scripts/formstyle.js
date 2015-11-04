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
}

setFormStyle();

$("form").keypress(function(e){
    var no_enter = true;
    if (e.which == 13) {
        if ($(e.target).prop('type') == 'password') {
            no_enter = false;
        }
        if ($(e.target).is('textarea')) {
            no_enter = false;
        }
        if (no_enter) {
            return false;
        }
   }
});

// Prevent the backspace key from navigating back.
$(document).unbind('keydown').bind('keydown', function (event) {
    var doPrevent = false;
    if (event.keyCode === 8) {
        var d = event.srcElement || event.target;
        if ((d.tagName.toUpperCase() === 'INPUT' &&
             (
                 d.type.toUpperCase() === 'TEXT' ||
                 d.type.toUpperCase() === 'PASSWORD' ||
                 d.type.toUpperCase() === 'FILE' ||
                 d.type.toUpperCase() === 'SEARCH' ||
                 d.type.toUpperCase() === 'EMAIL' ||
                 d.type.toUpperCase() === 'NUMBER' ||
                 d.type.toUpperCase() === 'DATE' )
             ) ||
             d.tagName.toUpperCase() === 'TEXTAREA') {
            doPrevent = d.readOnly || d.disabled;
        }
        else {
            doPrevent = true;
        }
    }

    if (doPrevent) {
        event.preventDefault();
    }
});
