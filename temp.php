<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

$date = date("Y-m-d",strtotime("2020-06-17"));	
$products = [2,16,27,63,28,29,12,76,20,3,44,49,38,78,79];
foreach($products as $product)
{
	$sql="SELECT id,client FROM discounts WHERE type = 'sd' AND product = $product AND status = 1";
	$discounts = mysqli_query($con, $sql) or die(mysqli_error($con));
	
	foreach($discounts as $discount)
	{
		$id = $discount['id'];
		$client = $discount['client'];
		$insertQuery="INSERT INTO discounts (date, product, type, client, amount, status)
			 VALUES
			 ( '$date' , $product, 'sd' , $client , 0 , 0 )";
		$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));
		
		$updateQuery="UPDATE discounts SET status = 0 WHERE Id = $id";
		$update = mysqli_query($con, $updateQuery) or die(mysqli_error($con));
	}
}
mysqli_close($con);
?>