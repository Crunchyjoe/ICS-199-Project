<!doctype html>
<html lang="en">
	<meta charset="utf-8">
	<head>
	<title>Register</title>
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
			padding-top: 10px;
			padding-right: 50px;
			padding-left: 5px;
			padding-bottom: 10px;
		}
  </style>
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
</head>

<body>
	<?php include('Header.php'); ?>
  <form name="registerform" action="Register.php" method="post">
		<div id="textentry">
			<label for="email">Email Address</label>
	    <input type="email" name="email" placeholder="example@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required></input>
			<label for="password">Password</label>
			<input type="password" name="password" placeholder="Password" minlength=8 maxlength=8 required></input>
			<label for="fname">First Name</label>
	    <input type="text" name="fname" placeholder="First Name" pattern="^[A-z]+$" required></input>
			<label for="lname">Last Name</label>
	    <input type="text" name="lname" placeholder="Last Name" pattern="^[A-z]+$" required></input>
		</div>

		<div id="privacytext" onscroll="scrolled(this)" class="parentDiv">
			<p id="desc">
				By registering for Bookverse, you agree that Bookverse now has access to the information you have given in any of it's forms. Bookverse may limit your access to the
				website for any reason without explanation. Bookverse will not have direct access to your financial information.
			</p>
		</div>
		<label for="privacytext">Do you accept the privacy policy?</label>
		<div id="yesno">
			<input type="radio" name="privacy" value="yes" id="yes" checked disabled>yes</input>
			<input type="radio" name="privacy" value="no" id="no" disabled>no</input>
		</div>
		<button type="submit">Register</button>

  </form>
</body>
<?php
  session_start();
  //$dbc = mysqli_connect('localhost', 'cst167', '439336', 'ICS199Group11_dev');
	require('mysqli_connect.php');
	$answer = $_POST['privacy'];

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($answer == "yes") {
			$legal = "Yes";
			$email = $_POST['email'];
			$password = $_POST['password'];
			$query = "INSERT INTO customer (email, password, customer_status, legal) VALUES ('$email', '$password', 'customer', '$legal')";

			if (mysqli_query($dbc, $query)){
				print '<p>registered Successfully! click <a href="Login2.php">here</a> to Log in</p>';
			}
			else {
				print '<p style="color: red;">Could not register because:<br>' .
						mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
						print ' mysqli_error($dbc)';
			}
		} else {
			echo '<p style="color: red;">You must accept the privacy policy</p>';
		}
	}

	function test_input($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
		$data = mysqli_real_escape_string($data);
  	return $data;
	}

 ?>
 <?php
  include('Footer.php');
 ?>
</html>
