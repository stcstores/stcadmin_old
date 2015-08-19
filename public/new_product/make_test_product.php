<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();

$api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);

$product = new NewProduct();

$_SESSION['new_product'] = $product;

$product->details['item_title']->set('NEW SINGLE TEST PRODUCT');
$product->details['var_type']->set(false);
$product->details['department']->set('Sports Shop');
$product->details['brand']->set('Any Brand');
$product->details['manufacturer']->set('Any Manufacturer');
$product->details['short_description']->set('Single item test product.');
$product->details['weight']->set(500);
$product->details['int_shipping']->set('TRUE');
$product->details['retail_price']->set(5.50);
$product->details['purchase_price']->set(5.50);
$product->details['shipping_price']->set(5.50);
$product->details['barcode']->set('0000000000000');
$product->details['shipping_method']->set('Packet');
$product->details['size']->set('Large');
$product->details['colour']->set('Red');
$product->details['height']->set(500);
$product->details['width']->set(500);
$product->details['depth']->set(500);
$product->details['material']->set('Wood');
$product->details['style']->set('Any');
$product->details['ebay_title']->set('eBay Title');
$product->details['ebay_description']->set('eBay Description');
$product->details['am_title']->set('Amazon Title');
$product->details['am_bullet_1']->set('Amazon Bullet One');
$product->details['am_bullet_2']->set('Amazon Bullet Two');
$product->details['am_bullet_3']->set('Amazon Bullet Three');
$product->details['am_bullet_4']->set('Amazon Bullet Four');
$product->details['am_bullet_5']->set('Amazon Bullet Five');
$product->details['am_description']->set('Amazon Description');
$product->details['shopify_title']->set('Shopify Title');
$product->details['shopify_description']->set('Shopify Description');
$product->details['ebay_title']->set('eBay Title');
$product->details['ebay_title']->set('eBay Title');

$filename = realpath('../images/favicon.png');
echo $filename;
$curlFile = curl_file_create($filename, 'image/jpeg', 'image.jpg');
$image = array('file' => $curlFile);
$response = $api -> uploadImage($image);
print_r($response);
$guid = $response[0]['FileId'];
$thumbPath = $response[0]['ThumbnailUrl'];
$fullPath = $response[0]['ImageUrl'];
$product->images->addImage($guid, $thumbPath, $fullPath);



header('Location: finish_product.php');
exit();