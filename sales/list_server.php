<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/rate.php';

	$clients = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name ASC");	
	foreach($clients as $client)
	{
		$clientMap[$client['id']] = $client['name'];
	}	

	$products = mysqli_query($con,"SELECT id,name FROM products ORDER BY name ASC");	
	foreach($products as $product)
	{
		$productMap[$product['id']] = $product['name'];
	}	

	$requestData= $_REQUEST;

	$columns = array( 
		0 =>'id', 
		1 =>'date', 
		2 =>'client', 
		3 => 'truck_no',
		4=> 'rate',
		5=> 'product',
		6=> 'qty',
		7=> 'bill_no',
		8=> 'customer_name',
		9=> 'customer_phone',
		10=> 'remarks'
	);

	// getting total number records without any search

	$sql = "SELECT id,date,client,truck_no,product,qty,bill_no,customer_name,customer_phone,remarks";
	$sql.=" FROM sales ORDER BY date DESC";
	$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 27');	
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


	$sql = "SELECT id,date,client,truck_no,product,qty,bill_no,customer_name,customer_phone,remarks";
	$sql.=" FROM sales where 1=1  ";

	if( !empty($requestData['columns'][0]['search']['value']) )
	{  
		$sql.=" AND id LIKE '".$requestData['columns'][0]['search']['value']."%' ";
	}

	if( !empty($requestData['columns'][1]['search']['value']) )
	{ 

		$pattern_day1 = '/^[0-9]{2}$/';
		$pattern_day2 = '/^[0-9]{2}-$/';
		$pattern_day3 = '/^[0-9]{2}-[0-9]{1}$/';
		
		$pattern_day_month1 = '/^[0-9]{2}-[0-9]{2}$/';
		$pattern_day_month2 = '/^[0-9]{2}-[0-9]{2}-$/';
		$pattern_day_month3 = '/^[0-9]{2}-[0-9]{2}-[0-9]{1}$/';
		$pattern_day_month4 = '/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/';
		$pattern_day_month5 = '/^[0-9]{2}-[0-9]{2}-[0-9]{3}$/';
		
		$pattern_month = '/^[a-z A-Z]/';

		$full_pattern = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/';
		if(preg_match($pattern_day1, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day2, $requestData['columns'][0]['search']['value']) || preg_match($pattern_day3, $requestData['columns'][0]['search']['value']))
		{
			$day_array[0] = $requestData['columns'][1]['search']['value'][0];
			$day_array[1] = $requestData['columns'][1]['search']['value'][1];
			$day = implode ('', $day_array);
			$sql.=" AND date LIKE '%".$day."' ";	
		}

		if(preg_match($pattern_day_month1, $requestData['columns'][0]['search']['value']) || preg_match($pattern_day_month2, $requestData['columns'][0]['search']['value']) || preg_match($pattern_day_month3, $requestData['columns'][0]['search']['value']) || preg_match($pattern_day_month4, $requestData['columns'][0]['search']['value']) || preg_match($pattern_day_month5, $requestData['columns'][0]['search']['value']))
		{
			$month_day_array[0] = $requestData['columns'][1]['search']['value'][3];
			$month_day_array[1] = $requestData['columns'][1]['search']['value'][4];
			$month_day_array[2] = $requestData['columns'][1]['search']['value'][2];
			$month_day_array[3] = $requestData['columns'][1]['search']['value'][0];
			$month_day_array[4] = $requestData['columns'][1]['search']['value'][1];
			
			$month_day = implode ('', $month_day_array);
			$sql.=" AND date LIKE '%".$month_day."' ";	

		}
		
		if( preg_match($pattern_month, $requestData['columns'][1]['search']['value']) )
		{
			$date = date_parse($requestData['columns'][1]['search']['value']);
			$month = $date['month'];
			$sql.=" AND month(date) = '".$month."' ";	
		}	

		if(	preg_match($full_pattern, $requestData['columns'][1]['search']['value'])	)
		{
			$full_date = date('Y-m-d', strtotime($requestData['columns'][1]['search']['value']));
			$sql.=" AND date LIKE '".$full_date."' ";	
		}
		
		else if(strcmp($requestData['columns'][1]['search']['value'] , 'to') != 0)	
		{
			$dates = explode( 'to', $requestData['columns'][1]['search']['value'] );
			$from = date('Y-m-d', strtotime($dates[0]));
			$to = date('Y-m-d', strtotime($dates[1]));
			$sql.=" AND date BETWEEN '".$from."' AND  '".$to."' ";	
		}	
		
	}

	if( !empty($requestData['columns'][2]['search']['value']) )
	{  //client
		$searchString = $requestData['columns'][2]['search']['value'];
		$arList =  mysqli_query($con, "SELECT id FROM clients WHERE name LIKE '%".$searchString."%' ") or die(mysqli_error($con).' LINE 114');	
		$firstEntry  = true;
		foreach($arList as $ar)
		{
			if($firstEntry)
				$sql.=" AND (client = '".$ar['id']."' ";		
			else
				$sql.=" OR client = '".$ar['id']."' ";		
			
			$firstEntry = false;
		}
				$sql.=")";		
	}

	if( !empty($requestData['columns'][3]['search']['value']) )
	{ //truck
		$sql.=" AND truck_no LIKE '%".$requestData['columns'][3]['search']['value']."%' ";
	}

	if( !empty($requestData['columns'][5]['search']['value']) )
	{ 
		$searchString = $requestData['columns'][5]['search']['value'];
		$productList =  mysqli_query($con, "SELECT id FROM products WHERE name LIKE '%".$searchString."%' ") or die(mysqli_error($con).' LINE 114');	
		$firstEntry  = true;
		foreach($productList as $product)
		{
			if($firstEntry)
				$sql.=" AND (product = '".$product['id']."' ";		
			else
				$sql.=" OR product = '".$product['id']."' ";		
			
			$firstEntry = false;
		}
				$sql.=")";		
	}

	if( !empty($requestData['columns'][6]['search']['value']) )
	{ 
		$sql.=" AND qty LIKE '".$requestData['columns'][6]['search']['value']."%' ";
	}

	if( !empty($requestData['columns'][7]['search']['value']) )
	{ 
		$sql.=" AND bill_no LIKE '".$requestData['columns'][7]['search']['value']."%' ";
	}

	if( !empty($requestData['columns'][8]['search']['value']) )
	{ 
		$sql.=" AND customer_name LIKE '".$requestData['columns'][8]['search']['value']."%' ";
	}

	if( !empty($requestData['columns'][9]['search']['value']) )
	{ 
		$sql.=" AND customer_phone LIKE '".$requestData['columns'][9]['search']['value']."%' ";
	}

	if( !empty($requestData['columns'][10]['search']['value']) )
	{ //cstl
		$sql.=" AND remarks LIKE '".$requestData['columns'][10]['search']['value']."%' ";
	}

	$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 148');	
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
		
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

	$query=mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 153 : '.$sql);	

	$data = array();
	$itemarray = array();
	$total = 0;
	while( $row=mysqli_fetch_array($query) ) {  // preparing an array

		$rate = getRate($row['date'],$row['product']);
		if($rate == null)
			$rate = 0;					
		
		$WD = getWD($row['date'],$row['product']);
		if($WD == null)
			$WD = 0;
		
		$CD = getCD($row['date'],$row['product'],$row['client']);
		if($CD == null)
			$CD = 0;					
		
		$SD = getSD($row['date'],$row['product'],$row['client']);
		if($SD == null)
			$SD = 0;												
		
		$nestedData=array(); 

		$nestedData[] = '<a href="edit.php?clicked_from=all_sales&id='.$row["id"].'">'.$row["id"].'</a>';
		$nestedData[] = date('d-m-Y',strtotime($row['date']));
		$nestedData[] = $clientMap[$row['client']];
		$nestedData[] = $row["truck_no"];
		$nestedData[] = $rate - $WD - $CD - $SD;
		$nestedData[] = $productMap[$row['product']];
		$nestedData[] = $row["qty"];
			$total = $total + $row["qty"];
		$nestedData[] = $row["bill_no"];
		$nestedData[] = $row["customer_name"];
		$nestedData[] = $row["remarks"];
		$data[] = $nestedData;
		
		$product = $productMap[$row['product']];
		if(!array_key_exists($product,$itemarray))
			$itemarray[$product] = $row["qty"];
		else
			$itemarray[$product] = $itemarray[$product] + $row["qty"];
	}

	// Split the array for display purpose

	$len = count($itemarray);
	$itemarray1 = array_slice($itemarray, 0, $len / 2);
	$itemarray2 = array_slice($itemarray, $len / 2);


	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data,   // total data array
				"itemarray1"		  => json_encode($itemarray1),				
				"itemarray2"		  => json_encode($itemarray2),							
				"total"           => $total 
				);

	echo json_encode($json_data);  // send data as json format

}
?>