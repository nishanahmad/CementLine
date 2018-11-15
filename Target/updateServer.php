<?php
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$month = (int)$_POST['month'];
	$year = (int)$_POST['year'];	
	
	foreach($_POST as $key => $value)
	{
		$arr = explode("-",$key);
		$arId = (int)str_replace('_',' ',$arr[0]);
		
		if(isset($arr[1]))
		{
			if($arr[1] == 'target')
			{
				$sql="UPDATE target SET target = '$value' WHERE client = '$arId' AND month = '$month' AND year = '$year' ";
				$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   
			}
			else if($arr[1] == 'rate')	
			{
				$sql="UPDATE target SET rate = '$value' WHERE client = '$arId' AND month = '$month' AND year = '$year' ";
				$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   			
			}	
			else if($arr[1] == 'pp')	
			{
				$sql="UPDATE target SET payment_perc = '$value' WHERE client = '$arId' AND month = '$month' AND year = '$year' ";
				$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   			
			}						
		}
	}
	
	header( "Location: index.php" );

	mysqli_close($con); 
}
else
{
	header( "Location: ../index.php" );
}	
?> 