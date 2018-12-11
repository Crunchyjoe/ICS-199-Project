<!doctype html>
<html>
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

        #footer {
            bottom:0;

            width:100%;
            height:60px;   /* Height of the footer */
            background: #3498DB;
            font-family: Arial, Consolas, "Courier New", monospace;
            font-size: 16px;
            color: white;
            padding: 10px;
        }
        #footer {
            bottom:0;

            width:100%;
            height:60px;   /* Height of the footer */
            background: #3498DB;
            font-family: Arial, Consolas, "Courier New", monospace;
            font-size: 16px;
            color: white;
            padding: 10px;
        }
        #empty_space {
		    height:200px;
	    }
		#totalprice{
			font-family: Arial, Consolas, "Courier New", monospace;
            font-size: 16px;
			border-radius: 10px;
			position: relative;
			top: 10%;
			left: 20%;
			width:80%;
			padding: 20px 50px;
			margin: auto;
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

<?php require_once('./config.php'); ?>
<?php
require ('mysqli_connect.php');
session_start();
$total = $_GET['total'];
$data_amt = (int)$total * 100;
$userID = $_SESSION["user_id"];
$numprods = $_GET['numprods'];
echo '<input type="hidden" name="numprods" value="<?php echo $numprods; ?>">';

 ?>
<div id = "totalprice">
<h1> Your total is: <p style="color: red;">&#36;<?php echo  "$total"; ?></h1>
<p> Click on "Pay with Card" to purchase your product </p>

<form action="charge.php" method="post" name="checkout">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-description="Payment checkout";
          data-amount="<?php echo $data_amt ?>";
          data-locale="auto">
  </script>
          <input type="hidden" name="data_amt" value="<?php echo $data_amt; ?>">
</form>
</div>


<div id="empty_space">
</div>
<div id="footer">
    <footer>
        Bookverse Inc.<br>
        8888 Interurban Road,Victoria, BC, Canada<br>
        Tel.: 250-888-8888		Fax: 250-888-8888
    </footer>
</div>
</html>