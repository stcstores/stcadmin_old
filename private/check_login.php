<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
//require_once($CONFIG['include']);
require_once($CONFIG['axevalley_tools']);

$login = false;

$database = new DatabaseConnection();

$query = "SELECT id FROM users";
$userIds = $database->selectQuery($query);

if(isset($_SESSION['userid'])){
    if ($_SESSION['timeout'] > time()){
        foreach ($userIds as $id) {
            if ($id['id'] == $_SESSION['userid']) {
                $login = true;
            }
        }
    } else {
        header('Location:/logout.php');
        exit();
    }
}

if ($login == false) {
    header('Location:/login.php');
    exit();
}