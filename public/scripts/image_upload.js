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
        var skuData = data[sku];
        $('#currentImages').append("<div id=sku" + sku + " class='skubox'>");
        var skuBox = $('#sku' + sku);
        skuBox.append("<h3>" + skuData['title'] + "</h3>");
        
        skuBox.append("<form enctype='multipart/form-data' method='post' id=addImage" + sku + " class=addImage >");
        var form = $('#addImage' + sku);
        var browseButton = $("<input type=file value='Get Data' id=browseButton" + sku + " name=" + sku + "[] multiple />");
        form.append(browseButton);
        browseButton.sku = sku;
        browseButton.change(function() {
            var buttonsku = this.id.replace('browseButton', '');
            uploadImages(buttonsku);
        });
        
        for (thisImage in skuData['images']) {
            var image = skuData['images'][thisImage];
            var imageNumber = thisImage;
            var imageThumb = image['thumbPath'];
            var imageGuid = image['guid'];
            console.log(sku);
            var primary = image['primary'];
            $("#sku" + sku).append("<div id=sku" + sku + "image" + imageNumber + " class=imagebox >");
            var imageDiv = $('#sku' + sku + "image" + imageNumber);
            
            if (primary == true) {
                imageDiv.addClass('primary');
                imageDiv.append('<p>Primary Image</p>');
            }
            imageDiv.append("<img src='" + imageThumb + "' />");
            if (primary == false) {
                var setPrimeButton = $("<input type=button value='Set Primary' />");
                setPrimeButton.click(setPrimaryGenerator(sku, imageGuid));
                imageDiv.append(setPrimeButton);
            }
            var removeButton = $("<input type=button value=Remove />");
            removeButton.click(removeImageGenerator(sku, imageGuid));
            imageDiv.append(removeButton);
        }
    }
}

function removeImageGenerator(sku, guid) {
    return function (event) {
        removeImage(sku, guid);
    };
}

function setPrimaryGenerator(sku, guid) {
    return function (event) {
        setPrimary(sku, guid);
    };
}

function removeImage(sku, guid){
    console.log(guid);
    disableInputs();
    var data = new FormData();
    data.append('remove', 1);
    data.append('sku', String(sku));
    data.append('guid', String(guid));
    $.ajax({
        url: '/new_product/edit_images.php',
        type: "POST",
        async: false,
        data: data ,
        contentType: false,
        cache: false,
        processData:false,
        complete: function(data) {
        }
    });
    reload();
}

function setPrimary(sku, guid){
    console.log(sku);
    disableInputs();
    var data = new FormData();
    data.append('setprime', 1);
    data.append('sku', String(sku));
    data.append('guid', String(guid));
    $.ajax({
        url: '/new_product/edit_images.php',
        type: "POST",
        async: false,
        data: data ,
        contentType: false,
        cache: false,
        processData:false,
        complete: function(data) {
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