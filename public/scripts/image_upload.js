var files;

function reload() {
    $.ajax({
        url: 'get_images.php',
        async: false,
        dataType: 'json',
        success: function(data){
            writeImages(data);
        }
    });
    
    enableInputs();
}

function writeImages(data) {
    $('#currentImages').html('');
    for (sku in data) {            
        $('#currentImages').append("<div id=sku" + sku + " class='skubox'>");
        var skuBox = $('#sku' + sku);
        skuBox.append("<h3>" + data[sku]['title'] + "</h3>");
        
        skuBox.append("<form enctype='multipart/form-data' method='post' id=addImage" + sku + " class=addImage >");
        var form = $('#addImage' + sku);
        form.append("<input type=file value='Get Data' id=browseButton" + sku + " name=" + sku + "[] multiple />");
        form.append("<input type=button value='Upload' onClick=uploadImages('" + sku + "') />");
        
        for (image in data[sku]['images']) {
            var imageNumber = image;
            var imageId = data[sku]['images'][image]['id'];
            var primary = data[sku]['images'][image]['primary'];
            $("#sku" + sku).append("<div id=sku" + sku + "image" + imageNumber + " class=imagebox >");
            var imageDiv = $('#sku' + sku + "image" + imageNumber);
            
            if (primary == true) {
                imageDiv.addClass('primary');
                imageDiv.append('<p>Primary Image</p>');
            }
            imageDiv.append("<img src='image.php?id=" + imageId + "' width=100 />");
            if (primary == false) {
                imageDiv.append("<input type=button value='Set Primary' onclick='setPrimary(" + imageId + ", " + sku + ")' />");
            }
            imageDiv.append("<input type=button value=Remove onclick='removeImage(" + imageId + ", " + sku + ")' />");
        }
    }
}

function removeImage(imageId, sku){
    disableInputs();
    $.ajax({
        url: 'edit_images.php',
        async: false,
        data: "remove=true&sku=" + sku + "&imageId=" + imageId ,
        dataType: 'GET',
        success: function(data){
            
        }
    });
    reload();
    
}

function setPrimary(imageId, sku){
    disableInputs();
    $.ajax({
        url: 'edit_images.php',
        async: false,
        data: "setprime=true&sku=" + sku + "&imageId=" + imageId ,
        dataType: 'GET',
        success: function(data){
            
        }
    });
    reload();
    
}

function writeErrors(errors) {
    console.debug(errors);
    $('#errors').html('');
    $('#errors').append(errors['responseText']);
    
}

function uploadImages(sku) {
    console.log(sku);
    var form = new FormData($('#addImage' + sku).get(0));

    form.append('sku', sku);
    
    disableInputs();
    
    $.ajax({
        url: "upload_images.php",
        type: "POST",
        data:  form,
        contentType: false,
        cache: false,
        processData:false,
        complete: function(data) {
            writeErrors(data);
            $('#browseButton').val('');
            reload();
        },
        error: function()  {
            
        }
    });
    

    
}

function disableInputs() {
    $('#currentImages').append('<img class=working src=/images/ajax-loader.gif alt=working />');
    $(':input').attr('disabled','disabled');
}

function enableInputs(){
    $(':input').removeAttr('disabled');
}

$(document).ready(function(){reload();});