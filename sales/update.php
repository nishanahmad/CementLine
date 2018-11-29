<?php
	require '../connect.php';	
	
	if(count($_POST)>0) 
	{
		$id = $_POST['id'];
		$date = $_POST['date'];
		$sqlDate = date("Y-m-d", strtotime($date));
		$truck = $_POST['truck'];
		$qty = $_POST['qty'];
		$cd = $_POST['cd'];
		$qd = $_POST['qd'];
		$sd = $_POST['sd'];
		$bill = $_POST['bill'];
		$customerName = $_POST['customerName'];
		$customerPhone = $_POST['customerPhone'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$remarks = $_POST['remarks'];
		
		if(empty($rate))
			$rate = 'null';
		if(empty($cd))
			$cd = 'null';
		if(empty($qd))
			$qd = 'null';
		if(empty($sd))
			$sd = 'null';		
		
		$update = mysqli_query($con,"UPDATE sales SET date='$sqlDate', truck_no='$truck', qty='$qty',
									 cd = $cd, qd = $qd, sd = $sd,  bill_no='$bill', 
									 address1='$address1', address2='$address2', customer_name='$customerName', 
									 customer_phone='$customerPhone', remarks='$remarks'
									 WHERE id = $id") or die(mysqli_error($con));				 	 

		  $url = 'todayList.php?client=all';
		  header( "Location: $url" );
	}																																				?>