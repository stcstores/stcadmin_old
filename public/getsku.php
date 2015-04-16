<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);
?>

<script type='text/javascript' src='scripts/jquery.zclip.min.js'></script>
<form id=get_sku class=form_section >
    <input type=button value='Get New SKU' name=new_sku id=new_sku_button onClick="getSku()"/>
    <input type=text value="" id=new_sku_text class=new_sku_text readonly />
    <input type=button name=copy_sku id=copy_sku value=Copy />
    <span id=hidden_tick>&#10004</span>
</form>

<script type='text/javascript'>
    
    $(document).ready(function(){
        $('#copy_sku').zclip({
            path:'http://www.steamdev.com/zclip/js/ZeroClipboard.swf',
            copy:function(){
                return $('#new_sku_text').val();
            },
            beforeCopy:function() {
              $('#new_sku_text').css  ('background', 'yellow');
            },
            afterCopy:function(){
                $('#new_sku_text').css('color', '#4A6905');
                $('#hidden_tick').css('display', 'inline');
                $('#new_sku_text').css  ('background', '#B2D467');
            }
        });
        
        $('#new_sku_text').zclip({
            path:'http://www.steamdev.com/zclip/js/ZeroClipboard.swf',
            copy:function(){
                return $('#new_sku_text').val();
            },
            beforeCopy:function() {
              $('#new_sku_text').css  ('background', 'yellow');
            },
            afterCopy:function(){
                $('#new_sku_text').css('color', '#4A6905');
                $('#hidden_tick').css('display', 'inline');
                $('#new_sku_text').css  ('background', '#B2D467');
            }
        });
    });
    
    function getSku() {
        disableInputs();
        $.ajax({
            url: 'generatesku.php',
            async: false,
            dataType: 'json',
            success: function(data){
                setSku(data);
            }
        });
    }
    
    function setSku(sku) {
        $('#new_sku_text').val(sku);
        enableInputs();
    }
    
    function disableInputs() {
        $('#currentImages').append('<img class=working src=images/ajax-loader.gif alt=working />');
        $(':input').attr('disabled','disabled');
    }
    
    function enableInputs() {
        $(':input').removeAttr('disabled');
    }

    
</script>

<?php require_once('../private/footer.php'); ?>