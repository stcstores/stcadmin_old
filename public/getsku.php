<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);
?>

<script type='text/javascript' src='scripts/jquery.zclip.min.js'></script>
<form id=get_sku class=form_section >
    <input type=button value='Get New SKU' name=new_sku id=new_sku_button />
    <input type=text value="" id=new_sku_text class=new_sku_text readonly />
</form>

<script type='text/javascript'>

    function getSku() {

        $.ajax({
            url: 'generatesku.php',
            async: false,
            dataType: 'json',
            complete: function(response){
                var sku = response['responseText'];
                console.log(sku);
                $('#new_sku_text').val(sku);
            }
        });
    }

    function setSku(sku) {
        $('#new_sku_text').val(sku);

    }

    function disableInputs() {
        $(':input').attr('disabled','disabled');
    }

    function enableInputs() {
        $(':input').removeAttr('disabled');
    }

    $('#new_sku_button').click(function() {
        disableInputs();
        var sku = getSku();

        enableInputs();
    });

</script>

<?php
require_once($CONFIG['footer']);
