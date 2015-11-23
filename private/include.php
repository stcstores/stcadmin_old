<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/lib/STCAdmin/STCAdmin.inc.php');
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/lib/LinnworksAPI/LinnworksAPI.inc.php');
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
    require_once($CONFIG['constants']);
    session_start();
    require_once($CONFIG['colours']);
    require_once($CONFIG['catch']);
    require_once($CONFIG['forms']);
