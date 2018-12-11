
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
			width: 85%
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

	
		#login_reminder{ /*Styles the message when user wants to see the view cart but not logged in yet*/
			font-size: 20px;
			font-family: Arial, Consolas, "Courier New", monospace;
			text-align: center;
			padding: 150px;
			margin-left: auto; /*center the reminder message*/
			margin-right: auto; 
	
		}
		
		div.container{
			display: block;   /*the menu displays as a block */
			color: black;		/*text color of menu */	
			padding: 8px 16px;
			align: center;
		}
		#empty_space {
		height:200px;
		}
		.footer{
			position: fixed;
			botton: 0px;
		}
		
	</style>
</head>
<body>
        <h2 align="center">Your Order History</h2>
        <div align="center">
            <table>
            <tr>
				<th width="8%">Order ID</th>
                <th width="30%" style="text-align:left">Book Name</th>
				<th width="15%"><strong>Book Cover</strong></th>
                <th width="10%">Quantity</th>
                <th width="10%">Price</th>
				<th width="10%">Sub-total</th>
                <th width="35%">Shopping Date</th>
            </tr>

        <?php
		
			require ('mysqli_connect.php');
			session_start();
			if(isset($_SESSION["user_id"])){
				$userID=$_SESSION["user_id"];
				$query = "SELECT oh.order_id, p.product_name,  p.product_img_dir, op.quantity, oh.date, op.price
							FROM orderproduct op, order_history oh, products p
							WHERE op.products_product_id = p.product_id 
							AND op.order_history_order_id = oh.order_id
							AND oh.customer_customer_id = $userID 
							ORDER BY order_id";
						  
						  //JOIN products p ON op.order_history_order_id=oh.order_id
				$result = mysqli_query($dbc,$query);
				$num_rows = mysqli_num_rows($result);
				
			if ($result)   {
				while ($row = mysqli_fetch_array($result)) {
					$img_dir = $row['product_img_dir'];
					echo $img_dir;
					print "<tr>";
					print "<td> " . $row['order_id'] . "</td>";
					print "<td style=\"text-align:left\"> " . $row['product_name']  . "</td>";
					print "<td> " . "<img src=\"$img_dir\" height=50 width=50></img></td>";
					print "<td> " . $row['quantity'] . "</td>";
					print "<td> " . "$". $row['price'] . "</td>";
					print "<td> " . "$" . $row['price'] * $row['quantity']. "</td>";
				
					print "<td> " . $row['date'] . "</td>";
					print "</tr>";
				}
			}
			else	{
				print "<h3> Query failed ==> " . $query . "</h3>";
			} 
		}

		?>
            </table>
        </div>
<div id="empty_space">
</div>	
<?php include ('Footer.php'); ?>
</body>
</html>
