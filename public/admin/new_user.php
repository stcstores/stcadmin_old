<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin($admin_required=true);
require_once($CONFIG['header']);

$password_missmatch = false;
$username_taken = false;

if ((isset($_POST['username'])) && (isset($_POST['password'])) && (isset($_POST['password_conf']))) {
    if ($_POST['password'] == $_POST['password_conf']) {
        if (!(userExists($_POST['username']))) {
                if ($_POST['admin'] == 'on') {
                    $admin = 'TRUE';
                } else {
                    $admin = 'FALSE';
                }
                addUser($_POST['username'], $_POST['password'], $admin);
                header('Location:/admin/index.php');
                exit();
        } else {
                $username_taken = true;
        }
    } else {
        $password_missmatch = true;
    }
    
}
?>
    
<form method=post id=login_form>
    <table>
        <tr>
                <td><label for=username>Username: </label></td>
                <td>
                        <input type=text id=username name=username <?php if (isset($_POST['username'])) { echo 'value="' . $_POST['username'] . '" ';} ?> required />
                        <?php if ($username_taken) {
                                echo "<p class=error>Username already in use</p>";
                        } ?>
                </td>
        </tr>
        <tr>
                <td><label for=password>Password: </label></td>
                <td><input type=password id=password name=password required /></td>
        </tr>
        <tr>
                <td><label for=password_conf>Confirm Password: </label></td>
                <td>
                        <input type=password id=password_conf name=password_conf required />
                        <?php if ($password_missmatch) {
                                echo "<p class=error>Passwords do not match</p>";
                        } ?>
                </td>
        </tr>
        <tr>
                <td>Admin: </td>
                <td><input type=checkbox name=admin /></td>
        </tr>
        <tr>
                <td><input type=submit value="Create New User" /></td>
        </tr>
    </table>
</form>

<?php


    echo "<script src=/scripts/forms.js ></script>";
    require_once($CONFIG['footer']);
?>