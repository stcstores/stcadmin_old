<?php
require_once('../private/include.php');
include('../private/header.php');
include('css/colour_scheme.php');

$colours = new ColourScheme('css/colours.txt');

$colours -> showScheme();

?>
    
