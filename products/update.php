<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$brand = $_POST['brand'];
	$status = $_POST['status'];
	
	$update = "UPDATE products SET name='$name',brand='$brand',isActive='$status' WHERE id=$id";
	
	$result = mysqli_query($con, $update) or die(mysqli_error($con));				 

	header( "Location: list.php");

}
else
	header( "Location:../index.php" );
?> 