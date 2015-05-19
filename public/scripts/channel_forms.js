var setTitle = $('<input class=small_button type=button value="Set to Item Title">');
$('#ebay_title').after(setTitle);
setTitle.click(function() {
    $('#ebay_title').val(product_title);
})

var setPrice = $('<input class=small_button type=button value="Set to Retail Price">');
$('#ebay_price').after(setPrice);
setPrice.click(function() {
    $('#ebay_price').val(product_price);
})

var setDescription = $('<input class=small_button type=button value="Set to Item Description">');
$('#ebay_description').after(setDescription);
setDescription.click(function() {
    $('#ebay_description').val(product_description);
})



var setTitle = $('<input class=small_button type=button value="Set to Item Title">');
$('#am_title').after(setTitle);
setTitle.click(function() {
    $('#am_title').val(product_title);
})

var setDescription = $('<input class=small_button type=button value="Set to Item Description">');
$('#am_description').after(setDescription);
setDescription.click(function() {
    $('#am_description').val(product_description);
})



var setTitle = $('<input class=small_button type=button value="Set to Item Title">');
$('#shopify_title').after(setTitle);
setTitle.click(function() {
    $('#shopify_title').val(product_title);
})

var setDescription = $('<input class=small_button type=button value="Set to Item Description">');
$('#shopify_description').after(setDescription);
setDescription.click(function() {
    $('#shopify_description').val(product_description);
})