<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();

if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}

if ( !empty($_POST) ) {
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    }
    add_extended_properties($product);

    if (true) { // error check
        $_SESSION['new_product'] = $product;
        header('Location: imageupload.php');
        exit();
    }
}

require_once($CONFIG['header']);
$database = new STCAdmin\Database();
$fields = $database->getFormFieldsByPage('extended_properties');
?>
<div class=small_form_container>
    <form method='post' enctype='multipart/form-data'>
        <table id='basic_info' class=form_section>
            <col span='1' style='width: 10%;'><col span='1' width=24%><col span='1' width=33%><col span='1' width=33%>
            <?php
                foreach ($fields as $field) {
                    ?>
                <tr>
                    <td class=form_table_field_name ><label for="<?php echo $field['field_name']; ?>"><?php echo $field['field_title']; ?></label></td>
                    <td class=form_table_input>
                        <?php echoInput($field, $product); ?>
                    </td>
                    <td class=form_field_table_description ><?php echo $field['field_description']; ?></td>
                </tr>
                <?php } ?>
        </table>
        <table class=form_nav>
            <tr>
                <td>
                    <input value='<< Previous' type=submit name=previous />
                    <input value='Next >>' type=submit name=next />
                </td>
            </tr>
        </table>
    </form>
</div>
<?php

$_SESSION['new_product'] = $product;
echo "<script src=/scripts/formstyle.js ></script>";
echo "<script src=/scripts/validation.js ></script>";

include($CONFIG['footer']);

?>
