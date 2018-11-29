<?php
	require '../connect.php';
	
	if(!empty($_POST['date'])) 
	{
		$date = date('Y-m-d',strtotime($_POST['date']));
		$product = (int)$_POST['product'];
		
		$sql = "SELECT * FROM rate WHERE date = '$date' AND product = $product";
		$query = mysqli_query($con,$sql) or die(mysqli_error($con));				 	 
		if(mysqli_num_rows($query)>0)
		{
			$rate = mysqli_fetch_array($query,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
			echo $rate['rate'].'-'.$rate['sd'];
		}
		else
		{
			echo null;			
		}
	}
