<?php
	require '../connect.php';
	
	$date = date('Y-m-d',strtotime($_POST['date']));
	$product = (int)$_POST['product'];
	
	$rateQuery = mysqli_query($con,"SELECT rate FROM rate WHERE date <= '$date' AND product = $product ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($rateQuery)>0)
	{
		$rate = mysqli_fetch_array($rateQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
		
		$discountQuery = mysqli_query($con,"SELECT amount FROM discounts WHERE date = '$date' AND product = $product AND type = 'wd' ") or die(mysqli_error($con));				 	 			
		if(mysqli_num_rows($discountQuery)>0)
		{
			$discount = mysqli_fetch_array($discountQuery,MYSQLI_ASSOC) or die(mysqli_error($con));	
			echo $rate['rate'].'-'.$discount['amount'];	
		}
		else
		{
			echo $rate['rate'];
		}	
	}
	else
	{
		echo null;			
	}
