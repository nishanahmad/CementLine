<?php
require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$date = date("Y-m-d");				 
								
	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['product'.$count]))
			break;
		
		$product = $_POST['product'.$count];
		$rate = (int)$_POST['rate'.$count];
		$sd = $_POST['sd'.$count];
		if(empty($sd))
			$sd = 'null';
		
		$query = mysqli_query($con,"SELECT * FROM rate WHERE date = '$date' AND product = '$product'");
		if(mysqli_num_rows($query) >0 )
		{
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$id = (int)$row['id'];
			$updateQuery ="UPDATE rate SET rate = $rate, sd = $sd WHERE id = $id "; 
			$update = mysqli_query($con, $updateQuery) or die(mysqli_error($con));	
		}	
		else
		{
			$insertQuery="INSERT INTO rate (date, product, rate, sd)
				 VALUES
				 ('$date', '$product', $rate, $sd)";

			$insert = mysqli_query($con, $insertQuery) or die(mysqli_error($con));				
		}
	}	
		
	header( "Location: ../index.php" );
}
else
{
	header( "Location: ../index.php" );
}	
?> 
					
