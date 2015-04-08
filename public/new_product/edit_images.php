<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);;

if (isset($_GET['remove'])) {
    $query = "DELETE FROM images WHERE sku='{$_GET['sku']}' AND id={$_GET['imageId']};";
    $imageDatabase = new DatabaseConnection();
    $imageDatabase->insertQuery($query);
    
    $selectQuery = "SELECT id FROM images WHERE sku='{$_GET['sku']}';";
    $imageResults = $imageDatabase->selectQuery($selectQuery);
    if (count($imageResults) > 0) {
        setImagePrimary($_GET['sku'], $imageResults[0]['id']);
    }
    
}

if (isset($_GET['setprime'])) {
    $sku = $_GET['sku'];
    $imageId = $_GET['imageId'];
    setImagePrimary($sku, $imageId);
}

?>