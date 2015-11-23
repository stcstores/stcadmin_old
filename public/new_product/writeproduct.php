<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

//echo $CSVFILEPATH;

if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
    $varSet = (count($product->variations) > 1);

    if ($varSet) {
        $newVarGroupFile = new STCAdmin\CSV\NewVarGroupFile();

        $newVarGroupFile->write();
    }

    $basicInfoFile = new STCAdmin\CSV\BasicInfoFile();
    $basicInfoFile->write();

    if ($varSet) {
        $addToVarGroupFile = new STCAdmin\CSV\AddToVarGroupFile();
        $addToVarGroupFile->write();
    }

    $imageUrlFile = new STCAdmin\CSV\ImageUrlFile();
    $imageUrlFile->write();

    $extendedPropertiesFile = new STCAdmin\CSV\ExtendedPropertiesFile();
    $extendedPropertiesFile->write();

    $ebayChannelFile = new STCAdmin\CSV\EbayFile();
    $ebayChannelFile->write();

    $stcStoresFile = new STCAdmin\CSV\STCStoresFile();
    $stcStoresFile->write();

    $amazonFile = new STCAdmin\CSV\AmazonFile();
    $amazonFile->write();


} else {
    echo "No Product";
}
echo "complete";
