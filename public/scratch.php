<?php
require_once('../private/include.php');
include('../private/header.php');
include('css/colour_scheme.php');

$hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 12]);

echo $hash;

echo "<br />";

echo password_verify('password', $hash);

echo "<br />";

echo (strlen($hash));



include('../private/footer.php');


    
