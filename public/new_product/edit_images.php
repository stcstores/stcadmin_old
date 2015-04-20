<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    
var_dump($_POST);

if (isset($_POST['remove'])) {
    
    $query = "DELETE FROM images WHERE sku='{$_POST['sku']}' AND id={$_POST['imageId']};";
    $imageDatabase = new DatabaseConnection();
    $imageDatabase->insertQuery($query);
    
    $selectQuery = "SELECT id FROM images WHERE sku='{$_POST['sku']}';";
    $imageResults = $imageDatabase->selectQuery($selectQuery);
    if (count($imageResults) > 0) {
        setImagePrimary($_POST['sku'], $imageResults[0]['id']);
    }
}

if (isset($_POST['setprime'])) {
    $sku = $_POST['sku'];
    $imageId = $_POST['imageId'];
    setImagePrimary($sku, $imageId);
}

?>