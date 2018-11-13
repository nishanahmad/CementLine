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
	$brand = (int)$_POST['brand'];

	$sql="INSERT INTO products (name,brand) VALUES ('$product',$brand)";

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