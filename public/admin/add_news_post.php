<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);

if (isset($_POST['title'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $database = new DatabaseConnection();
    $query = "INSERT INTO stcadmin_news (header, message) VALUES ('{$title}', '{$message}');";
    $database -> insertQuery($query);
}

?>

<div class=pagebox >
    <form method=post >
        <table>
            <tr>
                <td><label for=title >Title: </label></td>
                <td><input name=title size=100 /></td>
            </tr>
            <tr>
                <td><label for=message >Message:</label></td>
                <td><textarea name=message cols=75 rows=15 ></textarea></td>
            </tr>
            <tr>
                <td colspan=2><input type=submit value='Submit' /></td>
            </tr>
        </table>
    </form>
</div>