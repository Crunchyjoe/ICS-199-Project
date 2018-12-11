<!doctype html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="projCSS.css">
<?php
include('Header.html');
?>
<head>
	<meta charset="utf-8">
	<title>Add a book to the database</title>
</head>
<body>
<h1>add a book to the database</h1>

<?php
	session_start();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Handle the form.

		// Validate the form data:
		$problem = FALSE;
		// connect to the database
		//$dbc = mysqli_connect('localhost', 'cst167', '439336', 'ICS199Group11_dev');
		require('mysqli_connect.php');

		if ((!empty($_POST['price'])) && (!empty($_POST['name'])) && (isset($_FILES['img_dir'])) && (!empty($_POST['check_list']))) {
    	$price = mysqli_real_escape_string($dbc, $_POST['price']);
      $name = mysqli_real_escape_string($dbc, $_POST['name']);
			$img_dir = pathinfo($_FILES['img_dir']['name']);
			//$ext = $img_dir['extension'];
			//$newname = "$name." .$ext;
			//$target = 'Images/'.$newname;
			echo $target;
			}
      else {
				print '<p style="color: red;">Please Fill out all fields</p>';
				$problem = TRUE;
			}
                if((is_numeric($price)) && (isset($_FILES['img_dir']))){
                    if (!$problem) {
                            // Define the query:
                            $query = "INSERT INTO products(price, product_name, product_img_dir)
                            VALUES ('$price', '$name', '$target')";
                            if (mysqli_query($dbc, $query)){
                                $IDquery = "SELECT product_id FROM products WHERE product_name='$name'";
                                $result = mysqli_query($dbc, $IDquery);
                                $row = mysqli_fetch_array($result);
                                $prodID = $row['product_id'];

																if(move_uploaded_file($_FILES['img_dir']['tmp_name'], "Images/{$_FILES['img_dir']['name']}")){
																	print '<p>Uploaded image</p>';
																} else {
																	print '<p>Could not upload image</p>';
																}
                                //echo $prodID;
                                print '<p>Book added successfully</p>';
                                    }
                                    else {
                                        print '<p style="color: red;">Could not add the book because:<br>' .
                                            mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
                                            print ' mysqli_error($dbc)';
                                    }
                            foreach($_POST['check_list'] as $selected){
                                $catID = mysqli_real_escape_string($dbc, $selected);
                                $query2 = "INSERT INTO prod_cat VALUES ($prodID, $catID)";
                                if (mysqli_query($dbc, $query2)){
                                    print '<p>Category added successfully</p>';
                                    }
                                    else {
                                            print '<p style="color: red;">Could not add the book because:<br>' .
                                            mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query2 . '</p>';
                                            print ' mysqli_error($dbc)';
                                    }
                            }
                            mysqli_close($dbc);
                            }
                }
                else {
                    if (!is_numeric($price)){
                        print '<p style="color: red;">Price field must be a number</p>';
                    }
                    if (!isset($_FILES['img_dir'])){
                        print '<p style ="color: red;">You must upload a file</p>';
                    }
                }
		}
?>
<div id="bookform">
        <form action="Add_book.php" method="post" enctype="multipart/form-data">
                <p>Book price:</p>
                <input type="text" name="price">
                <p>Book name:</p>
								<input type="text" name="name">
                <p>Book Img Directory:</p>
								<input type="file" name="img_dir">
                <p>Book Genre:</p>
                <div id="checkarea">
                   Fiction: <input type="checkbox" name="check_list[]" value="1">
                   Non-Fiction: <input type="checkbox" name="check_list[]" value="2">
                   Sci-Fi: <input type="checkbox" name="check_list[]" value="3">
                   Romance: <input type="checkbox" name="check_list[]" value="4">
                   Kids: <input type="checkbox" name="check_list[]" value="5">
                   Comedy: <input type="checkbox" name="check_list[]" value="6">
                </div>
								<input type="submit" name="submit" value="Add book">
				</form>
</div>
</body>
<?php
 include('Footer.html');
// Include the footer.
?>
</html>
