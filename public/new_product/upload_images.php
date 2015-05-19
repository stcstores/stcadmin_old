<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['axevalley_tools']);
require_once($CONFIG['functions']);

if (isset($_POST['sku'])){
    
    if (is_array($_FILES)) {
        $i=0;
        $sku = $_POST['sku'];
        $errors = array();

        if (isset($_FILES[$sku]['tmp_name'])) {
            foreach ($_FILES[$sku]['tmp_name'] as $file) {
                if(is_uploaded_file($file)) {
                    $filename = $_FILES[$sku]['name'][$i];
                    if (hasImageExtenstion($filename)) {
                        $extension = pathinfo($filename, PATHINFO_EXTENSION);
                        if ( skuHasImages($sku) ) {
                            $primary = false;
                        } else {
                            $primary = true;
                        }
                        imageToDatabase($file, $sku, $primary, $extension);
                    } else {
                        $errors[] = 'Is not a valid image type. Must be .jpg, .jpeg, .png or .gif';
                    }
                } else {
                    $errors[] = "not uploaded file";
                }
            $i++;
            }
        }
        if (count($errors) == 0){
            $errors[] = 'No Errors';
        }
        foreach($errors as $err) {
            echo "<p class=error >{$err}</p>";
        }
    }
} else {
    echo "no sku";
}

?>