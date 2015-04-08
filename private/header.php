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
				<div>
					<a class=no_decoration href='/' ><h1>STC Admin</h1></a>
				</div>
				
				<?php if (isLoggedIn()) {?>
				
				<div id=logout>
					<p>Logged in as: <?php echo getCurrentUsername() ?></p>
					<p><a href='/logout.php'>Log out</a></p>
				</div>
				
				<?php } ?>
				
				<ul id=nav>
					<li><a href='/' >Home</a></li>
					<li><a href=/new_product/new_product_start.php >New Product</a></li>
					<li><a href=/new_product/testnewproduct.php >Test Product</a></li>
					<li><a href=/getsku.php >Get SKU</a></li>
					<li><a href=/new_product/archive.php >Product File Archive</a></li>
					<li><a href=/scratch.php >Scratch</a></li>					
					<li><a href=/cols.php >Colours</a></li>
				</ul>
			</div>
			<div id=content>