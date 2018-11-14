<?php
require '../connect.php';
$clients = mysqli_query($con,"SELECT * FROM clients") or die(mysqli_error($con));
foreach($clients as $client)
{
	$id = (int)$client['id']; 
	$sql="INSERT INTO target (client, year, month,rate,payment_perc, target)
		 VALUES
		 ($id, 2018, 11, 0.0,100,0)";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 		 
}