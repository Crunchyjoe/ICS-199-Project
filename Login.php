<!doctype html>
<html lang="en">
<head>
<head>
	<meta charset="utf-8">
  <?php
  include('Header.php');
  ?>
	<title>Login</title>
<!--this is all temporary, put into stylesheet later-->
  <style>
		input[type="email"], input[type="password"], input[type="text"], button[type="submit"] {
			display: block;
			margin: 5px;
		}

		input[type="radio"]{
			display: inline;
		}

		#privacytext {
			width: 200px;
			height: 100px;
			overflow-y: scroll;
			overflow-x: hidden;
			float: left;
			display: block;
			margin: 10px;
				}

		#desc {
			color: #666666;
			width:180px;
			font-family: Candara,Trebuchet MS,sans-serif;
			font-size: 12px;
			font-weight: bold;
			line-height: 18px;
				}

		#yesno {
			display: block;
				}

		#textentry {
			border: 2px solid black;
			border-radius: 10px;
			position: relative;
			padding: 0px;
		}
		
		div.register_container {
			height: 20em;
			width: 30em;
			position: relative 
			}
	
		div.register_container{

		background: lightgrey;
		position: absolute;
		top: 25em;
		left: 40em;
		margin-right: -50%;
		transform: translate(-50%, -50%);
		}
		
		#empty_space {
		height:300px;
	}
  </style>
</head>
<script>
var form = document.getElementById('privacytext');
//this function sees if total scrolled by user is equal to height of scrollbar
function scrolled(o){
	if(o.offsetHeight + o.scrollTop == o.scrollHeight){
		document.getElementById('yes').disabled = false;
		document.getElementById('no').disabled = false;
	}
}


</script>

<?
$product_id = $_GET['addprod'];
?>

<body>
	<div class="register_container">
	<label for="registerform"><strong>Enter your account information to log in: </strong></label>
	<form name="registerform" action="Login.php" method="get">
	<br>
		<label for="email">Email Address</label>
			<input type="email" name="email" placeholder="example@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required></input>
		<label for="password">Password</label>
			<input type="hidden" name="addprod" value="<?php print $_GET['addprod']; ?>">
			<input type="password" name="password" placeholder="Password" minlength=8 maxlength=8 required></input>
		<button type="submit">Login</button>
		<div id="privacytext" onscroll="scrolled(this)" class="parentDiv">
			<p id="desc">
				By registering for Bookverse, you agree that Bookverse now has access to the information you have given in any of it's forms. Bookverse may limit your access to the
				website for any reason without explanation. Bookverse will not have direct access to your financial information.
			</p>
		</div>
		<br>
		<label for="privacytext">Do you accept the privacy policy?</label>
		<div id="yesno">
			<input type="radio" name="privacy" value="yes" id="yes" checked disabled>yes</input>
			<input type="radio" name="privacy" value="no" id="no" disabled>no</input>
		</div>
  </form>
  </div>
</body>

<?php
	session_start();
	require('mysqli_connect.php');
	$answer = $_GET['privacy'];
	$product_id = $_GET['addprod'];
	$answer = 'yes';

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$email = test_input($_GET['email']);
		$password = test_input($_GET['password']);
		$testquery = "SELECT legal FROM customer WHERE email='$email'";
		$result = mysqli_query($dbc, $testquery);
		$row = mysqli_fetch_array($result);
		if(mysqli_query($dbc, $testquery)){
				$query2 = "UPDATE customer SET legal = '$answer' WHERE email='$email'";
				mysqli_query($dbc, $query2);
		}

		if ($answer == "yes") {
			$query = "SELECT email, password FROM customer WHERE email='$email' AND password='$password'";
			if (mysqli_query($dbc, $query)){
				#print '<p> logged in succesfully!</p>';
			
				$query3 = "SELECT customer_id FROM customer WHERE email='$email' AND password='$password'";
				$result3 = mysqli_query($dbc, $query3);
				$row3 = mysqli_fetch_array($result3);
				$_SESSION['user_id'] = $row3['customer_id'];
				$_SESSION['user_loginid'] = 1;
				if(isset($_GET['addprod'])){
					if($_SESSION['user_id'] != 0){
						$nextpage = "View_cart.php?addprod=$product_id";
						$_SESSION['nextpage'] = $nextpage;
						$_SESSION['addprod'] = $_GET['addprod'];
					}else{
						$nextpage = "Login.php?addprod=$product_id";
						$_SESSION['nextpage'] = $nextpage;
						$_SESSION['addprod'] = $_GET['addprod'];
					}
					
					print "<h3 align=\"center\"><a href=\"$nextpage\">Click here</a> to see your shopping cart.</h3>";
					header("Location: http://deepblue.cs.camosun.bc.ca/~cst116/ICS199/Project/Code/Index.php");

				}else{
					print "<h3 align=\"center\">Please log in to go shopping or see your shopping cart.</h3>";
				}
			}
			else {
				print '<p style="color: red;">Could not Log in because:<br>' .
						mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
						print ' mysqli_error($dbc)';

			}
		} else {
			echo '<p style="color: red;">You must accept the privacy policy to Login</p>';
		}
	}

	function test_input($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
	}

 ?>

</html>

<footer>
<div id="empty_space">
</div>
<?php include('Footer.php');?>
</footer>
