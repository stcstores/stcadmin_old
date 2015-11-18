<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
//require_once($CONFIG['include']);
session_start();
require_once($CONFIG['functions']);
require_once($CONFIG['login_functions']);

$failedLoggin = false;

if (isLoggedIn()) {
    header('Location:/index.php');
}


if ((isset($_POST['username'])) && (isset($_POST['password']))) {
    if (login($_POST['username'], $_POST['password'])) {
        header('Location:/index.php');
    } else {
        $failedLoggin = true;
    }
}


require_once($CONFIG['header']);

?>
<div id=login_container >

    <h2>Welcome to STCAdmin</h2>

    <form id=login_form method=post>
        <h3>Please Log In</h3>
        <table>
            <tr>
                <td><label for="username">Username: </label></td>
                <td><input type=text size=25 name=username id=login_username class=login required <?php
                if (isset($_POST['login_username'])) {
                    $username = htmlspecialchars($_POST['login_username']);
                    echo 'value="' . $username . '" ';
                }
                ?>/></td>
            </tr>
            <tr>
                <td><label for="password" >Password: </label></td>
                <td><input type="password" size="25" name="password" id="login_password" class=login required /></td>
            </tr>
            <?php
            if ((isset($failedLoggin)) && ($failedLoggin == true)) {
                echo '<tr><td colspan="2" class="error" >Authentication Failed</td></tr>';
            }
            ?>
            <tr>
                <td colspan=2 ><input id=login_button type=submit value='Login' /></td>
            </tr>
        </table>
        <div class=errors id=login_errors>

        </div>
    </form>

</div>


<?php
    echo "<script src=scripts/formstyle.js ></script>";
    require_once($CONFIG['footer']);
?>
