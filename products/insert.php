<html>
<body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$product = $_POST['name'];

	$sql="INSERT INTO products (name) VALUES ('$product')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 	 
		 
	header( "Location: list.php" );

	mysqli_close($con);
}
else
{
	header( "Location: ../index.php" );
}	
?> 
</body>
</html>