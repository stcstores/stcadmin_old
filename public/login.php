<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
//require_once($CONFIG['include']);
session_start();
require_once($CONFIG['functions']);
require_once($CONFIG['axevalley_tools']);


if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];
    
    $users = getUsers();
    
    foreach ($users as $user) {
        if ($user['username'] == $username) {
            if ($user['password'] == $password) {
                $_SESSION['userid'] = $user['id'];
                $_SESSION['timeout'] = time() + 60*60*2; //2 hours
                header('Location: /index.php');
                exit();
            }
        }
    }
    
}

require_once($CONFIG['header']);
?>



<form id=login_form method=post>
    <table>
        <tr>
            <td>Username: </td>
            <td><input type=text size=25 name=login_username id=login_username class=login /></td>
        </tr>
        <tr>
            <td>Password: </td>
            <td><input type=password size=25 name=login_password id=login_password class=login /></td>
        </tr>
        <tr>
            <td><input id=login_button type=submit value='Login' /></td>
        </tr>
    </table>
    <div class=errors id=login_errors>
        
    </div>
</form>


<?php
    echo "<script src=scripts/forms.js ></script>";
    require_once($CONFIG['footer']);
?>