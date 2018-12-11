<!doctype html>
<html>
<head>
   <meta charset="utf-8">
	<title>View Cart</title>
	<?php include ('Header.php'); ?>
	<style>
		table, th, td {
			border: 1px solid black;
			font-family: Arial, Consolas, "Courier New", monospace;
			text-align: center;
			padding: 5px 25px 5px;
			border-collapse: collapse;
		}
		table{
			align: center;
			width: 90%
		}

		th{
			text-weight: bold;
			color: white;
			align: center;
			background-color: #3498DB;
		}
		
		tr:nth-child(odd){
			background: lightgrey;
		}

		#remove_cart{
			float: center;
		}
		#container{
			align: center;
		}
		button{
			border-radius: 5px;
			align: center;
		}
		#empty_space {
		height:100px;
		}
	</style>
</head>
<body>


<?php

	session_start();
	require ('mysqli_connect.php');
	
	if (!isset($_SESSION['user_id']))   {
		$_SESSION["user_id"] = 0;
		$_SESSION["user_loginid"] = "";
	}
	$userID = $_SESSION["user_id"];


	if (isset($_GET['addprod'])){
		# if product is already in the cart, add 1 to quantity and update cart
		$prod_id = $_GET['addprod'];

		$query_1 = "SELECT quantity FROM cart
						WHERE products_product_id = '$prod_id'
						AND customer_customer_id = '$userID'";
		$result_1 = mysqli_query($dbc,$query_1);
		$row_1 = mysqli_fetch_array($result_1);

		if ($row_1['quantity'] > 0){

			$updated_quantity = $row_1['quantity'] + 1;
			$query_2 = "UPDATE cart SET quantity = $updated_quantity where products_product_id = '$prod_id' ";
			mysqli_query($dbc,$query_2);
		}

		# if product is not in the cart, insert row with quantity equal to 1
		$num_rows = mysqli_num_rows($result_1);
		if ($num_rows == 0){
			$query_3 = "INSERT INTO cart (customer_customer_id, products_product_id, quantity )
								VALUES ($userID, $prod_id, 1)";
			$result_3 = mysqli_query($dbc,$query_3);
			$last = mysqli_affected_rows($dbc);
		}
	}

	if (isset($_GET['decrease'])){
		# if product is already in the cart, add 1 to quantity and update cart
		$prod_id = $_GET['decrease'];

		$query_1 = "SELECT quantity FROM cart
						WHERE products_product_id = '$prod_id'
						AND customer_customer_id = '$userID'";
		$result_1 = mysqli_query($dbc,$query_1);
		$row_1 = mysqli_fetch_array($result_1);

		if ($row_1['quantity'] > 0){

			$updated_quantity = $row_1['quantity'] - 1;
			$query_2 = "UPDATE cart SET quantity = $updated_quantity where products_product_id = '$prod_id' ";
			mysqli_query($dbc,$query_2);
		}

		if ($row_1['quantity'] < 2){
			$query_3 = "DELETE FROM cart
						WHERE products_product_id = '$prod_id'
						AND customer_customer_id = '$userID'";
			$result_3 = mysqli_query($dbc,$query_3);
			$last = mysqli_affected_rows($dbc);
		}
	}

	if (isset($_GET['remove'])){
		$prod_id = $_GET['remove'];
		$query_1 = "DELETE FROM cart
						WHERE products_product_id = '$prod_id'
						AND customer_customer_id = '$userID'";
		$result_1 = mysqli_query($dbc,$query_1);
		$row_1 = mysqli_fetch_array($result_1);
	}
	if (isset($_GET['RemoveAll'])){

		$query_1 = "DELETE FROM cart
						WHERE customer_customer_id = '$userID'";
		$result_1 = mysqli_query($dbc,$query_1);
		$row_1 = mysqli_fetch_array($result_1);
	}
?>
        <h2 align="center">View Shopping Cart</h2>
        <div align="center">
            <table 	>
            <tr>
				<th width="10%">Book ID</th>
                <th width="30%" style="text-align:left">Book Name</th>
				<th width="15%"><strong>Book Cover</strong></th>
                <th width="10%">Quantity</th>
                <th width="10%">Price</th>
                <th width="15%">Sub-total</th>
                <th width="10%"  colspan="3">Adjust Quantity</th>
            </tr>
        <?php
			$query = "SELECT customer_customer_id, product_img_dir, products_product_id, product_name, price, quantity FROM
			          cart,products
					  where cart.products_product_id = products.product_id and
					        customer_customer_id = " . $_SESSION["user_id"] ;
			  $result = mysqli_query($dbc,$query);
				$num_rows = mysqli_num_rows($result);
        // start of liam's code
        $totalquery = "SELECT quantity, price, quantity * price AS total FROM cart, products  WHERE customer_customer_ID = $userID
        AND product_id = products_product_id;";
        $tresult = mysqli_query($dbc, $totalquery);
        $total;
        while ($trow = mysqli_fetch_array($tresult)){
          $total = $total + $trow['total'];
        }
        $total = $total * 1.12;
        // end of liam's code
			if ($result)   {
      
				while ($row = mysqli_fetch_array($result)) {
					$img_dir = $row['product_img_dir'];
					print "<tr>";
					print "<td> " . $row['products_product_id'] . "</td>";
					print "<td style=\"text-align:left\"> " . $row['product_name']  . "</td>";
					print "<td><img src=\"$img_dir\" height=60 width=60></img></td>";;
					print "<td> " . $row['quantity'] . "</td>";
					print "<td> " . "$". $row['price'] . "</td>";
					print "<td> " . "$". $row['price'] * $row['quantity'] . "</td>";
					print "<td><a href=\"View_cart.php?decrease=" . $row['products_product_id'] . "\"><button type=\"submit\"  > - </td>";
					print "<td  color=\"red\"><a href=\"View_cart.php?remove=" . $row['products_product_id'] . "\"><button type=\"submit\" > x </td>";
					print "<td><a href=\"View_cart.php?addprod=" . $row['products_product_id'] . "\"><button type=\"submit\"  > + </td>";
					print "</tr>";
				}
				
			# code BELOW does calculations for:  $ of all items, GST, $ after GST in the shopping cart,
			require ('mysqli_connect.php');
			session_start();
			$userID=$_SESSION["user_id"];
			# query_8 is an arbituary number. It's used to retrieve data from database
			$query_8 = "SELECT price, quantity FROM cart,products
					WHERE cart.products_product_id = products.product_id 
					AND customer_customer_id = " . $_SESSION["user_id"] ;
			$result_8 = mysqli_query($dbc,$query_8);
			if(result_8){
				while($row_8 = mysqli_fetch_array($result_8)){
				$sum += $row_8['quantity'] * $row_8['price'];
				$gst += $sum * 0.12;
				$total = $sum + $gst;
				}
				print "<tr>";
				print "<td colspan=\"5\" style=\"text-align:right\">" . "Sum:" . "</td> " . "<td>" . "$". $sum . " </td>" 
					. "<td colspan=\"3\" rowspan=\"4\" style=\"text-align:left\">" . 
							"Button Icon Functions: ". "<br>"."<br>".
							"<button> - </button> : decrement"."<br>"."<br>".
							"<button> x </button> : remove" . "<br>"."<br>".
							"<button> + </button> : increment" . "<br>".
					" </td>";
				print "</tr>";
				print "<tr>";
				print "<td colspan=\"5\" style=\"text-align:right\"> GST(12%): </td>" . "<td>" . "$". $gst . "</td>";
				print "</tr>";
				print "<tr>";
				print "<td colspan=\"5\" style=\"text-align:right\"> Total: </td>" . " <td>" . "$". $total. " </td>";
				print "</tr>";
								
				print "<tr>";
				print "<td colspan=\"5\" style=\"text-align:left\">" 
					  . "<div id=\"remove_cart\">" . "<a href=\"View_cart.php?RemoveAll=" . True . "\">" 
					  . "<button type=\"submit\" style=\"color: grey;\">Remove All from Cart</div>" 
					  . "</td>" ;  #print out the Remove All button on the left of Check Out button
					  
				print " <td>" . 
					  "<div id=\"check_out\" align=\"center\"><a href=\"Checkout.php?total=$total\"><button type=\"submit\" name=\"checkout\" style=\"color: red; font-weight: bold;\"> Checkout </div>" 
					  . " </td>";
				print "</tr>";
			}
			# code ABOVE does calculations for:  $ of all items, GST, $ after GST in the shopping cart,
						
			}else{
				print "<h3> Query failed ==> " . $query . "</h3>";
			}
		?>
            </table>
        </div>
	<br>
	
	<?php	#print "<div id=\"remove_cart\"><a href=\"View_cart.php?RemoveAll=" . True . "\"><button type=\"submit\" >Remove All from Cart</div>"; ?>



</body>
<div id="empty_space">
</div>
<?php include('Footer.php'); ?>
</html>
