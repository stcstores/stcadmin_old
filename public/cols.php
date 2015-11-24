<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
require_once($CONFIG['header']);

$colours = new ColourScheme('css/colours.txt');

$colours -> showScheme();

require_once($CONFIG['footer']);
?>
