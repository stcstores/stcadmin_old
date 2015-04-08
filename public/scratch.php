<?php
require_once('../private/include.php');
include('../private/header.php');
include('css/colour_scheme.php');

function addOrdinalSuffix($day) {
    if (substr($day, -2, 2) == 11) {
        $suffix = 'th';
    } elseif (substr($day -2, 2) == 12) {
        $suffix = 'th';
    } elseif (substr($day -2, 2) == 13) {
        $suffix = 'th';
    } elseif (substr($day -1, 1) == 1) {
        $suffix = 'st';
    } elseif (substr($day -1, 1) == 2) {
        $suffix = 'nd';
    } elseif (substr($day -1, 1) == 3) {
        $suffix = 'rd';
    } else {
        $suffix = 'th';
    }
    echo substr($day, -2, 2);
    echo "<br />";
    echo $suffix;
    echo "<br />";
    return $day . "<sup>{$suffix}</sup>";
}

echo addOrdinalSuffix(13);



include('../private/footer.php');


    
