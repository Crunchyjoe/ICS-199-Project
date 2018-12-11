<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>View by Genre</title>
	<link rel="stylesheet" href="default.css">
	<?php include ('Header.php'); ?>
</head>

<body>
<h1 align="center">View by Genre</h1>

<form action="Index.php" method="post" align="center">
		<select align="center" name="select_genre" >		<p align="center">Select a genre: </p>
			<option name="select_genre" value="0">All</option>
			<option name="select_genre" value="1">Fiction</option>
			<option name="select_genre" value="2">Non-Fiction</option>
			<option name="select_genre" value="3">Sci-Fi</option>
			<option name="select_genre" value="4">Romance</option>
			<option name="select_genre" value="5">Kid</option>
			<option name="select_genre" value="6">Comedy</option>
		</select>
		<button align="center" type="submit" name="submit"><strong>Select Category</strong></button>
		<br>
		<br>
</form>

<table>
	<tr>
		<th width="5%"><strong>Book ID</strong></th>
        <th width="30%" style="text-align:left"><strong>Book Name</strong></th>
        <th width="10%"><strong>Price</strong></th>
		<th width="15%"><strong>Book Cover</strong></th>
        <th width="10%"></strong>Actions<strong></th>
    </tr>

	<style>
		table, th, td {
		align: center;
		border: 1px solid black;
		font-family: Arial, Consolas, "Courier New", monospace;
		text-align: center;
		padding: 5px 25px 5px;
		border-collapse: collapse;
	}
	
	table{
		width: 90%;
		margin: auto;
		margin: auto;
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

</style>

<?php
	session_start();#
	include ('mysqli_connect.php');


        $category = $_POST['select_genre'];

        if ($category == ""){
            $category = 0;
        }
        if ($category == 0){
            $query = 'SELECT * FROM products';
        }
        else {
            $query = "select product_id, product_name, product_img_dir, price
        from products, prod_cat
        where products.product_id = prod_cat.products_product_id and
        prod_cat.categories_categorie_id = '$category'";
        }

	$result = mysqli_query($dbc,$query);
	

	if ($result){
     		$row_count = mysqli_num_rows($result);
			
			if($_SESSION["user_id"] != 0){#
				$nextpage = "View_cart.php?";#
			} else {#
				$nextpage = "Login.php?";#
			}#

            while ($row = mysqli_fetch_array($result)) {
							$name = $row['product_name'];
							$ID = $row['product_id'];
							$price = $row['price'];
							$img_dir = $row['product_img_dir'];
							print "<tr>";
							print "<td>$ID</td>";
							print "<td style=\"text-align:left\">$name</td>";
							print "	<td>&dollar;$price</td>";
							print "<td><img src=\"$img_dir\" height=60 width=60></img></td>";
							print "<td><a href=\"$nextpage addprod=$ID\"><button type=\"submit\" type=\"add_to_cart\" >Add To Cart</td>";
							print "</tr>";
					}
	}
	mysqli_close($dbc);
?>
</table>
<br>
<br>
<?php include ('Footer.php'); ?>
</body>
</html>
