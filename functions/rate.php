<?php
function getRate($date,$product)
{
	require '../connect.php';
	
	$rateQuery = mysqli_query($con,"SELECT rate FROM rate WHERE date <= '$date' AND product = $product ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($rateQuery)>0)
	{
		$rate = mysqli_fetch_array($rateQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
		
		return $rate['rate'];
	}
	else
	{
		return null;			
	}
}

function getWD($date,$product)
{
	require '../connect.php';
	
	$wdQuery = mysqli_query($con,"SELECT amount FROM discounts WHERE date = '$date' AND product = $product AND type = 'wd'") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($wdQuery)>0)
	{
		$wd = mysqli_fetch_array($wdQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
		
		return $wd['amount'];
	}
	else
	{
		return null;			
	}
}
?>