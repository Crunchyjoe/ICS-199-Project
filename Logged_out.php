<?php
session_start();
$_SESSION["user_id"] = 0;
#print $_SESSION["user_id"] "<h3>-------------------</h3>";
print "<h3>Logged out seccuful</h3>";
header('Location: http://deepblue.cs.camosun.bc.ca/~cst116/ICS199/Project/Code/Index.php');

?>