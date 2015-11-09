<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['include']);
    checkLogin();
    require_once($CONFIG['header']);

if (isset($_SESSION['new_product'])) {
    $product = $_SESSION['new_product'];
} else {
    header('Location: new_product_start.php');
    exit();
}

 ?>

        <div id=main>
            <form id=upload_imageForm >
                <!--<input type=button onclick=reload() value='Reload' />-->
            </form>
            <div id=currentImages>
                <!--Images go here-->
            </div>
            <div id=errors>
                <!--Error messages go here-->
            </div>
            <table class=form_nav>
                <tr>
                    <td>
                        <input value='<< Previous' type=button name=previous onclick="window.location.href='new_linnworks_product_shopify.php'" />
                        <input value='Next >>' type=button name=next onclick="window.location.href='finish_product.php'" />
                    </td>
                </tr>
            </table>
        </div>
        <script src=/scripts/image_upload.js ></script>
        <script src=/scripts/formstyle.js ></script>

<?php

require_once($CONFIG['footer']);
