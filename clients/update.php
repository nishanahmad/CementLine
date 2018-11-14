<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$mobile = $_POST['mobile'];
	$shop = $_POST['shop'];
	
	if(empty($mobile))
		$mobile = 'null';
	
	if(empty($shop))
		$sql = "UPDATE clients SET name='$name',mobile=$mobile,shop=NULL WHERE id=$id";
	else
		$sql = "UPDATE clients SET name='$name',mobile=$mobile,shop='$shop' WHERE id=$id";
	
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 

	header( "Location: view.php?id=".$id );

}
else
	header( "Location:../index.php" );
?> 