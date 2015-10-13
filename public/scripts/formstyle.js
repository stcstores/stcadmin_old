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
    if (e.which == 13) {
       if ($(e.target).prop('type') != 'password' ){
           return false;
       }
   }
});