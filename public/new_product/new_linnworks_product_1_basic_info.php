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
            if (count($product->variations) > 0) {
                header('Location: new_linnworks_product_var_table.php');
                exit();
            } else {
                header('Location: new_linnworks_product_var_setup.php');
                exit();
            }
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


<div class=small_form_container>
    <form method='post' enctype='multipart/form-data'>
        <table id='basic_info' class=form_section>
            <col span='1' width= 10% />
            <col span='1' width= 33% />
            <col span='1' width= 33% />
            <!--<col span='1' style='width: 33%;' />-->
            <tr>
                <td class=form_table_field_name >
                    <label for="item_title">Item Title</label>
                </td>
                <td class=form_table_input>
                    <input id=item_title name=item_title type=text value='<?php echo $product->details['item_title']->text;?>' maxlength=80 required  />
                </td>
                <td class=form_field_table_description >
                    <p>Required. Between 5 and 80 characters. Must not contain <a href="/new_product/specialcharacters.php" tabindex=-1>special characters</a>.</p>
                    <p>This title is for internal identification and should NOT contain search terms or keywords.</p>
                </td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="ebay_title">eBay Title</label>
                </td>
                <td class=form_table_input>
                    <input id=ebay_title name=ebay_title type=text value='<?php echo $product->details['ebay_title']->text;?>' maxlength=80 />
                </td>
                <td class=form_field_table_description >
                    <p>Required. Between 5 and 80 characters. Must not contain <a href="/new_product/specialcharacters.php" tabindex=-1>special characters</a>.</p>
                    <p>This title will be used for the eBay listing and should contain any necessary keywords. If left blank the item title will be used.</p>
                </td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="var_type">Variations</label>
                </td>
                <td class=form_table_input>
                    <input name=var_type type=checkbox <?php if ($product->details['var_type']->value == true) { echo 'checked'; } ?> />
                </td>
                <td class=form_field_table_description >Is the product a variation item</td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="department">Department</label>
                </td>
                <td class=form_table_input>
                    <select id=department name=department>
                        <?php
                            $departments = $api->get_category_names();
                            foreach ($departments as $dept){
                                echo "<option value='" . $dept . "' ";
                                    if ($dept == $product->details['department']->text) {
                                        echo " selected ";
                                    }
                                    echo ">" . $dept . "</option>" . $dept . "\n";
                            }
                        ?>
                    </select>
                </td>
                <td class=form_field_table_description >Department to which this product belongs</td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="brand">Brand</label>
                </td>
                <td class=form_table_input>
                    <input id=brand name=brand type=text value='<?php echo $product->details['brand']->text;?>' size=20 required />
                </td>
                <td class=form_field_table_description >The brand of the product</td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="manufacturer">Manufacturer</label>
                </td>
                <td class=form_table_input>
                    <input id=manufacturer name=manufacturer type=text value='<?php echo $product->details['manufacturer']->text;?>' size=20  />
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
                            $shippingMethods = $api->get_packaging_group_info();
                            foreach ($shippingMethods as $shippingMethod){
                                $method = $shippingMethod['name'];
                                if($method != 'Default') {
                                    echo "<option value='" . $method . "' ";
                                    if ($method == $product->details['shipping_method']->text) {
                                        echo " selected ";
                                    }
                                    echo ">" . $method . "</option>" . $method . "\n";

                                }
                            }
                        ?>
                    </select>
                </td>
                <td class=form_field_table_description >Select the method by which the item will be posted</td>
            </tr>
            <tr>
                <td class=form_table_field_name >
                    <label for="short_description">Description</label>
                </td>
                <td class=form_table_input>
                    <textarea rows=4 id=short_description name=short_description ><?php echo $product->details['short_description']->text;?></textarea>
                </td>
                <td class=form_field_table_description >Product description text</td>
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
    </form>
</div>
<?php

$_SESSION['new_product'] = $product;

echo "<script src=/scripts/formstyle.js ></script>";
echo "<script src=/scripts/validation.js ></script>";
echo "<script> validateForm();</script>";

include($CONFIG['footer']);

?>
