<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['constants']);

if (isset($_GET['id'])) {
	header("Content-type: image/jpeg");
	$imageDatabase = new DatabaseConnection();
	$query = "SELECT image FROM images WHERE id={$_GET['id']}";
    $images = $imageDatabase->selectQuery($query);
    echo $images[0]['image'];
} else {
    header('Location:/index.php');
}
?>
