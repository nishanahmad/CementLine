<?php
function updateUserDetails($oldSale,$newSale)
{
	require '../connect.php';

	$products = mysqli_query($con,"SELECT id,name FROM products WHERE isActive = 1 ORDER BY id ASC");
	foreach($products as $product)
	{
		$productMap[$product['id']] = $product['name'];
	}
	
	$arObjects = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name");
	foreach($arObjects as $arObject)
	{
		$arId = $arObject['id'];
		$arMap[$arId] = $arObject['name'];
	}
		
	$id = $newSale['id'];
	$user = $_SESSION["user_name"];
	$userId = $_SESSION["user_id"];
	$dateTime = date('Y-m-d H:i:s');	
	
	$unlocked = false;
	
	if($oldSale['date'] != $newSale['date'])
	{
		$oldValue = date('d-m-Y',strtotime($oldSale['date']));
		$newValue = date('d-m-Y',strtotime($newSale['date']));
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Date', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}
	if($oldSale['client'] != $newSale['client'])
	{
		$oldValue = $arMap[$oldSale['client']];
		$newValue = $arMap[$newSale['client']];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Client', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));	
		$unlocked = true;		
	}	
	if($oldSale['truck_no'] != $newSale['truck_no'])
	{
		$oldValue = $oldSale['truck_no'];
		$newValue = $newSale['truck_no'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Truck', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;				
	}	
	if($oldSale['product'] != $newSale['product'])
	{
		$oldValue = $productMap[$oldSale['product']];
		$newValue = $productMap[$newSale['product']];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Product', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}	
	if($oldSale['qty'] != $newSale['qty'])
	{
		$oldValue = $oldSale['qty'];
		$newValue = $newSale['qty'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Qty', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}		
	if($oldSale['discount'] != $newSale['discount'])
	{
		$oldValue = $oldSale['discount'];
		$newValue = $newSale['discount'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Discount', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}		
	if($oldSale['remarks'] != $newSale['remarks'])
	{
		$oldValue = $oldSale['remarks'];
		$newValue = $newSale['remarks'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Remarks', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}	
	if($oldSale['bill_no'] != $newSale['bill_no'])
	{
		$oldValue = $oldSale['bill_no'];
		$newValue = $newSale['bill_no'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Bill', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}	
	if($oldSale['order_no'] != $newSale['order_no'])
	{
		$oldValue = $oldSale['order_no'];
		$newValue = $newSale['order_no'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Order No', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}			
	if($oldSale['customer_name'] != $newSale['customer_name'])
	{
		$oldValue = $oldSale['customer_name'];
		$newValue = $newSale['customer_name'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Cust Name', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}	
	if($oldSale['customer_phone'] != $newSale['customer_phone'])
	{
		$oldValue = $oldSale['customer_phone'];
		$newValue = $newSale['customer_phone'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Cust Phone', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;		
	}	
	if($oldSale['address1'] != $newSale['address1'])
	{
		$oldValue = $oldSale['address1'];
		$newValue = $newSale['address1'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Address1', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));	
		$unlocked = true;				
	}	
	if($oldSale['address2'] != $newSale['address2'])
	{
		$oldValue = $oldSale['address2'];
		$newValue = $newSale['address2'];
		
		$sql="INSERT INTO sale_edits (sale_id, edited_on, edited_by, field, old_value, new_value)
			 VALUES
			 ($id, '$dateTime', '$user', 'Address2', '$oldValue', '$newValue')";

		$insert = mysqli_query($con, $sql) or die(mysqli_error($con));
		$unlocked = true;				
	}		
	
	$query = mysqli_query($con, "SELECT * FROM tally_sale_check WHERE sale = '$id'");
	if(mysqli_num_rows($query) > 0 && $unlocked)
	{
		$update ="UPDATE tally_sale_check SET status = 'UNLOCKED', unlocked_by = $userId WHERE sale = '$id'";		
		$result = mysqli_query($con, $update);				 			 			
	}				
}