<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
?>

<!Doctype html>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>STC Admin</title>
		<link rel="stylesheet" type="text/css" href="/css/stcadmin.php">
		<link rel="icon" type="image/png" href="/images/favicon.png">
		<script src="/scripts/jquery-1.11.2.min.js"></script>
	</head>
<body>
	<div id=wrapper>
		<div id=header>
			<div id=topper>
<?php
if (STCAdmin\UserLogin::isLoggedIn()) {
	echo "\t\t\t\t<div id=logout class='pagebox'>\n";
	echo "\t\t\t\t\t<p>Logged in as: " . STCAdmin\UserLogin::getCurrentUsername()  . "</p>\n";
	echo "\t\t\t\t\t<p><a href='/logout.php'>Log out</a></p>\n";
	echo "\t\t\t\t</div>\n";
}
?>
				<div>
					<a class=no_decoration href='/' ><h1>STC Admin</h1></a>
				</div>
			</div>
<?php
if (STCAdmin\UserLogin::isLoggedIn()) {
	require_once($CONFIG['navbar']);
}
?>
		</div>
		<div id=content>
