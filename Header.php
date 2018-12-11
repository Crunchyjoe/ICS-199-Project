<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>
		<?php // Print the page title.
			if (defined('TITLE')) { // Is the title defined?
				print TITLE;
			} else { // The title is not defined.
			print 'Bookverse Book Store';
			}
		?>
	</title>
	
	<link rel="stylesheet" href="default.css">
	<style> 
		
		ul {
			list-style-type: none;  /* This line gets rid of the dot before the text on the list*/
			margin: 0;
			padding: 15px;    /* the height of the grey menu bar */
			background-color: grey;
			font-family: Arial, Consolas, "Courier New", monospace;
			font-size: 16px;
			text-align:center;
			
		}
		
		li { 
			 
			display: inline; 		/* This line makes the list horizontal*/
		}
		li a {
			margin-left: auto; /*center the reminder message*/
			margin-right: auto;
			display: block;   /*the menu displays as a block */
			color: white;		/*text color of menu */	
			padding: 8px 16px;
			text-decoration: none;
			display: inline;  /* This line makes the list horizontal*/
		}
		
		li a:hover {
			background-color: #555;
			color: white;
			padding: 15px /* the height of the menu block when hover over */
		}
		


</style>
</head>
<header>Bookverse Book Store</header>

		<nav>
			<ul>
				<li><a href="Index.php">Category Home </a></li>
				<li><a href="View_cart.php">View Shopping Cart </a></li>
				<li><a href="Order_history.php">View Order History </a></li>
				<li><a href="Register.php"> Register</a></li>
				<li><a href="About_us.php"> About Us</a></li>
				<li><a href="Login.php">Login </a></li>
				<?php // Print the page title.
				session_start();
				if (isset($_SESSION["user_id"])) { // Is the title defined?
					print "<li><a href=\"Logged_out.php\">Logout</a></li>";
				}
				?>
			</ul>
		</nav>

	
		<main container class="siteContent">
		<!-- BEGIN CHANGEABLE CONTENT. -->
