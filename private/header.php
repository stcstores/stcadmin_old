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
		<script src="/scripts/jquery-1.11.2.min.js"></script>
	</head>
	<body>
		<div id=wrapper>
			<div id=header>
				<div id=topper>
					<?php if (isLoggedIn()) {?>
					
					<div id=logout class="pagebox">
						<p>Logged in as: <?php echo getCurrentUsername() ?></p>
						<p><a href='/logout.php'>Log out</a></p>
					</div>
				
					<?php } ?>
					<div>
						<a class=no_decoration href='/' ><h1>STC Admin</h1></a>
					</div>
				</div>
				
				
				
				
				<?php
				if (isLoggedIn()) {
					require_once($CONFIG['navbar']);
				}				
				?>
				
				
			</div>
			<div id=content>