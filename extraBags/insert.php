<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$date = $_POST['date'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$client = $_POST['client'];
	$product = $_POST['product'];
	$qty = $_POST['qty'];
	$remarks = $_POST['remarks'];

	$sql="INSERT INTO extra_bags (date, client, product, qty, remarks)
		  VALUES
		  ('$sqlDate', '$client', '$product' ,'$qty', '$remarks')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	header( "Location: new.php" );

	mysqli_close($con);
}
else
{
	header( "Location: ../index.php" );
}	
?> 