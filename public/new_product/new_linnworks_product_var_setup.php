<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();

if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}

if (isset($_POST['variation_types']) && isset($_POST['variations'])) {
    $variationTypes = json_decode($_POST['variation_types'], true);
    $variationList = json_decode($_POST['variations'], true);
    $product -> variations = array();
    foreach ($product->keyFields as $keyField => $val) {
        $product->keyFields[$keyField] = false;
        foreach ($variationTypes as $variationType) {
            if ($keyField == $variationType) {
                $product->keyFields[$keyField] = true;
            }
        }
    }
    foreach ($variationList as $variation) {
        $new_variation = new NewVariation($product);
        foreach ($variation as $key => $value) {
            $new_variation -> details[$key] -> set($value);
        }
        $product -> variations[] = $new_variation;
    }

    if (isset($_POST['Previous'])) {
        header('Location: new_linnworks_product_1_basic_info.php');
        exit();
    } else {
        header('Location: new_linnworks_product_var_table.php');
        exit();
    }
}

require_once($CONFIG['header']);
?>

<div class="">
    <h2>Set Variations for <?php echo $product->details['item_title']->text; ?></h2>
    <div>
        <table id="add_variation_types" class="form_section">

        </table>
    </div>
    <br />
    <div>
        <table id="add_variations" class="form_section">
            <col width=10% />
            <col width=40% />
            <col width=5% />
            <col width=5% />
            <col width=45% />
        </table>
    </div>
    <br />
    <div>
        <table id="list_of_variations" class="form_section">

        </table>
    </div>
    <br />
    <div id="var_error" class="hidden" ></div>
    <form method="post" id="var_form" enctype="multipart/form-data">
        <table class="form_nav">
            <tr>
                <td>
                    <input value="<< Previous" type="submit" name="previous" />
                    <input value="Next >>" type="submit" name="next" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script src="/scripts/var_form_validate.js"></script>

<?php
    $_SESSION['new_product'] = $product;
?>
<script>
    keyFields = <?php echo json_encode(getKeyFields()); ?>;
</script>
<script src=/scripts/var_setup.js ></script>
<script src=/scripts/formstyle.js ></script>
<script src=/scripts/validation.js ></script>

<?php

include($CONFIG['footer']);

?>
