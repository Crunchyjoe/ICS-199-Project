<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="default.css">

  <title>
      <?php // Print the page title.
      if (defined('TITLE')) { // Is the title defined?
          print TITLE;
      } else { // The title is not defined.
          print 'Bookverse Book Store';
      }
      ?>
  </title>
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
    #empty_space {
		height:300px;
	}

	.footer {
		font-family: Arial, Consolas, "Courier New", monospace;
		color: white;
		position: relative;
		right: 0;
		bottom: 0;
		left: 0;
		padding: 1rem;
		background-color: #3498DB;
		text-align: left;
	}
	#charge_worked{
		font-family: Arial, Consolas, "Courier New", monospace;
            font-size: 18px;
			border-radius: 10px;
			position: relative;
			left: 20%;
			width:80%;
			padding: 50px;
			margin: auto;
	}
	#order_id{
		font-family: Arial, Consolas, "Courier New", monospace;
            font-size: 18px;
			border-radius: 10px;
			position: relative;
			top: 10em;
			left: 20%;
			width:80%;
			padding: 15px 50px;
			margin: auto;
			}
  </style>
  
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
<?php
  session_start();
  require_once('config.php');
  require ('mysqli_connect.php');

  $userID = $_SESSION["user_id"];
  $checkresult = mysqli_query($dbc, "SELECT product_id, quantity, price FROM cart, products WHERE customer_customer_id = $userID
  AND product_id = products_product_id");
  mysqli_query($dbc, "INSERT INTO order_history (date, customer_customer_id) VALUES (CURDATE(), $userID)");
  $orderID = mysqli_insert_id($dbc);
  print '<div id=order_id>' .'order ID:' . $orderID . '</div>';
  $ordered = false;

  while ($cartrow = mysqli_fetch_array($checkresult)){
    $quantity = $cartrow['quantity'];
    $price = $cartrow['price'];
    $prodID = $cartrow['product_id'];
    if(mysqli_query($dbc, "INSERT INTO orderproduct VALUES ($prodID, $orderID, $quantity, $price)")){
      $ordered = true;
    } else {
      echo ("error: " . mysqli_error($dbc));
      $ordered = false;
    }

  }

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $totalamt = $_POST['data_amt'];

  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $totalamt,
      'currency' => 'cad'
  ));

$amount = number_format(($totalamt / 100), 2);
  echo '<div id="charge_worked">Successfully charged $'.'<strong style="color:red">' .$amount. '</strong>' .' <br>' . '<br> ' . 'Thank you for shopping at Bookverse</div>';
  
  //print information to a document to be sent as an email
$emailquery = mysqli_query($dbc, "SELECT email FROM customer WHERE customer_id = $userID");
$datequery = mysqli_query($dbc, "SELECT date FROM order_history WHERE customer_customer_id = $userID
AND order_id = $orderID");
$result = mysqli_query($dbc, "SELECT product_name, price, quantity FROM 
			          cart,products
					  where cart.products_product_id = products.product_id and
					        customer_customer_id = " . $_SESSION["user_id"]) ;
$emailrow = mysqli_fetch_array($emailquery);
$orderrow = mysqli_fetch_array($datequery);
$allProducts = "";
while ($row = mysqli_fetch_array($result)) {
	$allProducts = $allProducts . "Product Name ==> " . $row['product_name'] . ", Quantity ==> " . $row['quantity'] . ", Total price of products purchase ==> " . $row['price'] * $row['quantity'] . " \n";
}
$email = $emailrow['email'];
//$email = "liampatrickturnerheming@gmail.com";
$date = $orderrow['date'];
$msg = "Thank you for making a purchases from Bookverse! \nYou spent a total of " . $amount . " on this date: " . $date . " \nThese are the things you purchased: \n" . $allProducts . "";
$filename = "mail" . $orderID . ".txt";
$emailtext = fopen($filename, "w")or die("Unable to open text file ==> " . $filename);
fwrite($emailtext, $msg);
//mail($email,"Your Order",$msg);

  if(ordered){
    mysqli_query($dbc, "DELETE FROM cart WHERE customer_customer_id = $userID");
  }
  
  // Clear the cart:
  unset($_SESSION['cart']);
  
?>
  <div id="empty_space">
  </div>
  <div class="footer">
    
      Bookverse Inc.<br>
      8888 Interurban Road,Victoria, BC, Canada<br>
      Tel.: 250-888-8888		Fax: 250-888-8888
 
  </div>
</html>
