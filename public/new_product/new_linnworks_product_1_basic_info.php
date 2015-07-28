<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();

if ( !empty($_POST) ) {
    $product = add_basic_info();
    if (isset($_POST['previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    }
    
    if (true) { // error check
        $_SESSION['new_product'] = $product;
        
        if ($product->details['var_type']->value == true) {
            header('Location: new_linnworks_product_var_setup.php');
            exit();
        }
        
        header('Location: new_linnworks_product_2_extended_properties.php');
        exit();
    }
    
} else {
    if (isset($_SESSION['new_product'])){
        $product = $_SESSION['new_product'];
    } else {
        $product = new NewProduct();
    }
}

require_once($CONFIG['header']);

$api = new LinnworksAPI($_SESSION['username'], $_SESSION['password']);

?>



<form method='post' enctype='multipart/form-data'>
    <table id='basic_info' class=form_section>
        <col span='1' style='width: 10%;' />
        <col span='1' style='width: 24%;' />
        <col span='1' style='width: 33%;' />
        <col span='1' style='width: 33%;' />
        <tr>
            <td class=form_table_field_name >
                <label for="item_title">Item Title</label>
            </td>
            <td class=form_table_input>
                <input id=item_title name=item_title type=text value='' size=50 required  />
            </td>
            <td class=form_field_table_description >
                Required. Between 5 and 50 characters. Must not contain <a href="/new_product/specialcharacters.php" tabindex=-1>special characters</a>.
            </td>
        </tr>
        <tr>
            <td class=form_table_field_name >
                <label for="var_type">Variations</label>
            </td>
            <td class=form_table_input>
                <input name=var_type type=checkbox />
            </td>
            <td class=form_field_table_description >Does the product have variations?</td>
        </tr>
        <tr>
            <td class=form_table_field_name >
                <label for="department">Department</label>
            </td>
            <td class=form_table_input>
                <select id=department name=department>
                    <?php
                        $departments = $api->getCategoryNames();
                        foreach ($departments as $dept){
                            echo "<option value='" . $dept . "' >" . $dept . "</option>" . $dept . "\n";
                        }
                    ?>
                </select>
            </td>
            <td class=form_field_table_description >Department to which this product will belong</td>
        </tr>
        <tr>
            <td class=form_table_field_name >
                <label for="brand">Brand</label>
            </td>
            <td class=form_table_input>
                <input id=brand name=brand type=text value='' size=20  />
            </td>
            <td class=form_field_table_description >The brand of the product</td>
        </tr>
        <tr>
            <td class=form_table_field_name >
                <label for="manufacturer">Manufacturer</label>
            </td>
            <td class=form_table_input>
                <input id=manufacturer name=manufacturer type=text value='' size=20  />
            </td>
            <td class=form_field_table_description >The manufacturer of the product</td>
        </tr>
        <tr>
            <td class=form_table_field_name >
                <label for="shipping_method">Shipping Method</label>
            </td>
            <td class=form_table_input>
                <select id=shipping_method name=shipping_method>
                    <?php
                        $shippingMethods = $api->getPackagingGroupInfo();
                        foreach ($shippingMethods as $shippingMethod){
                            $method = $shippingMethod['name'];
                            echo "<option value='" . $method . "' >" . $method . "</option>" . $method . "\n";
                        }
                    ?>
                </select>
            </td>
            <td class=form_field_table_description >Select the method by which the item will be posted. Select courier for items too large to send by Royal Mail.</td>
        </tr>
        <tr>
            <td class=form_table_field_name >
                <label for="short_description">Short Description</label>
            </td>
            <td class=form_table_input>
                <textarea rows=4 cols=45 id=short_description name=short_description required ></textarea>
            </td>
            <td class=form_field_table_description >A brief description of the product. Primarily used for identification within Linnworks</td>
        </tr>
    </table>
    <table class=form_nav>
        <tr>
            <td>
                <input value='<< Previous' type=submit name=previous />
                <input value='Next >>' type=submit name=next />
            </td>
        </tr>
    </table>

<?php

$_SESSION['new_product'] = $product;

echo "<script src=/scripts/formstyle.js ></script>";
echo "<script src=/scripts/validation.js ></script>";
echo "<script> validateForm();</script>";

include($CONFIG['footer']);

?>