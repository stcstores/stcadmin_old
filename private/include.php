<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/lib/STCAdmin/STCAdmin.inc.php');
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/lib/LinnworksAPI/LinnworksAPI.inc.php');
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['constants']);
    //require_once($CONFIG['axevalley_tools']);
    //require_once($CONFIG['csv']);
    //require_once($CONFIG['new_product_class']);
    session_start();
    require_once($CONFIG['login_functions']);
    require_once($CONFIG['colours']);
    require_once($CONFIG['functions']);
    require_once($CONFIG['catch']);
    require_once($CONFIG['forms']);
    //require_once($CONFIG['api_class']);


?>
