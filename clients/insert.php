<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION['user_name']))
{
	$name = $_POST['name'];
	$shop = $_POST['shop'];
	$mobile = $_POST['mobile'];

	$sql="INSERT INTO clients (name,shop,mobile) VALUES ('$name','$shop','$mobile')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 	 
		 
	header( "Location: list.php" );

	mysqli_close($con);
}
else
{
	header( "Location: ../index.php" );
}	
?> 