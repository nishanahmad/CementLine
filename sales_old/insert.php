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
	$truck = $_POST['truck'];
	$product = $_POST['product'];
	$qty = $_POST['qty'];
	$discount = $_POST['bd'];	
	$remarks = $_POST['remarks'];
	$bill = $_POST['bill'];
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	
			
	if(empty($discount))
		$discount = null;	
	
	$sql="INSERT INTO sales (date, client, truck_no, product, qty, discount, remarks, bill_no, customer_name, customer_phone, address1, address2,entered_by,entered_on)
		 VALUES
		 ('$sqlDate', '$client', '$truck', '$product', '$qty', ".var_export($discount, true).", '$remarks', '$bill', '$customerName', '$customerPhone', '$address1', '$address2', '$entered_by', '$entered_on')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 	 
		 
	header( "Location: new.php" );
	
	mysqli_close($con);
}
else
	header( "Location: ../index.php" );

?>