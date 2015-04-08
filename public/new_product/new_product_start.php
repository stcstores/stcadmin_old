<?php
session_start();
unset($_SESSION['new_product']);
header('Location: new_linnworks_product_1_basic_info.php');
exit();
?>