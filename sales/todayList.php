<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	echo "LOGGED USER : ".$_SESSION["user_name"] ;	

	require '../connect.php';

	$id = $_GET['client'];
	if($id != 'all')
		$result = mysqli_query($con,"SELECT * FROM sales  WHERE client=$id AND date >= CURDATE() order by bill_no asc ") or die(mysqli_error($con));				 	 	
	else
		$result = mysqli_query($con,"SELECT * FROM sales WHERE date >= CURDATE() order by bill_no asc  ") or die(mysqli_error($con));				 	 
								
	$clients = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name ASC");	
	foreach($clients as $client)
		$clientMap[$client['id']] = $client['name'];
	
	$products = mysqli_query($con,"SELECT id,name FROM products ORDER BY name ASC");	
	foreach($products as $product)
		$productNameMap[$product['id']] = $product['name'];

	//$rates = mysqli_query($con,"SELECT MAX(date),product,rate FROM rate GROUP BY product");	
	$rates = mysqli_query($con,"SELECT product, rate FROM rate WHERE id IN ( SELECT MAX(date) FROM rate GROUP BY product )") or die(mysqli_error($con));				 	 	
	foreach($rates as $rate)
	{
		var_dump($rate);
		$rateMap[$rate['product']] = $rate['rate'];
	}	
		
	$i=0;
	$productMap = array();
	$mainMap = array();
	$qty=0;
	$total = 0;
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{   
		$productId = $row['product'];
		if (array_key_exists($productId,$productMap))
		{   
			$productMap[$productId] = $productMap[$productId] + $row["qty"];
			$total = $total + $row["qty"];
		}	
		else
		{
			$productMap[$productId] = $row["qty"];
			$total = $total + $row["qty"];
		}	

		$mainMap[$i]['id'] = $row['id'];
		$mainMap[$i]['date'] = date('d-m-Y', strtotime($row['date']));
		$mainMap[$i]['client'] = $clientMap[$row['client']];
		$mainMap[$i]['truck'] = $row['truck_no'];
		$mainMap[$i]['product'] = $productNameMap[$row['product']];
		$mainMap[$i]['truck'] = $row['truck_no'];
		$mainMap[$i]['qty'] = $row['qty'];
		$mainMap[$i]['truck'] = $row['truck_no'];
		$mainMap[$i]['bill'] = $row['bill_no'];
		$mainMap[$i]['name'] = $row['customer_name'];
		$mainMap[$i]['phone'] = $row['customer_phone'];
		$mainMap[$i]['remarks'] = $row['remarks'];
		
		$i++;
	}	
		?>
<html>
	<style>
		.rateTable{
			width:30%;
			border-collapse:collapse;
		}
		.rateTable th{
			padding: 5px;
			color : #000000;
		}
		.rateTable td{
			padding: 5px;
			color : #000000;
		}			
	</style>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="../bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
		<title>Sales List</title>
		<link rel="stylesheet" type="text/css" href="../css/styles.css" />
		<style>
			@media only screen and (max-width: 900px) {
				.desktop{
					display: none;
				}	
		</style>		
	</head>
	<body>
		<form name="frmsales" method="post" action="" >
			<div style="width:100%;">
			<div align="center" style="padding-bottom:5px;">
				<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
				<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
			</div>
			<br>
			<div align="center">
				<select name="client" id="client" onchange="document.location.href = 'todayList.php?client=' + this.value" class="txtField">
					<option value = "">--SELECT--</option>
					<option value = "all">ALL</option>																						    <?php
					foreach($clients as $client)
					{																															?>
						<option value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>																			<?php	
					}																											?>
				</select>
			<br/><br/>
			<table class="rateTable">
				<tr>
					<th>Product</th>
					<th>Qty</th>						
					<th>Rate</th>
					<th style="width:20%;">Discount</th>					
				</tr>			
			<?php				
			foreach($productMap as $product=>$qty)
			{																																		?>
				<tr>
					<td><?php echo $productNameMap[$product];?></td>
					<td><?php echo $qty;?></td>						
					<td><?php if(isset($rateMap[$product])) echo $rateMap[$product].'/-';?></td>						
					<td><?php if(isset($discountMap[$product])) echo $discountMap[$product].'/-';?></td>						
				</tr>
								<?php
			}?> 
				<tr>
					<th>Total</th>
					<th><?php echo $total;?></th>
					<th></th>						
					<th></th>						
				</tr>
			</table>
			</div>				
			<br>
			<br>
			<table width="100%" class="table-responsive">
				<tr class="tableheader">
					<td class="desktop">EDIT</td>
					<td class="desktop">Date</td>
					<td>AR</td>
					<td class="desktop">TRUCK NO</td>
					<td>PRODUCT</td>
					<td>QTY</td>
					<!--td>RATE</td-->
					<td>BILL NO</td>
					<td>CUST. NAME</td>
					<td class="desktop">CUST. PHONE</td>
					<td>REMARKS</td>
				</tr>																																															<?php
				foreach($mainMap as $index => $row) 
				{																																																?>	
					<tr class="blue">
						<td class="desktop"><a href="edit.php?id=<?php echo $row['id']; ?>" class="link"><img alt='Edit' title='Edit' src='../images/edit.png' width='20px' height='20px' hspace='10' /></a></td>  
						<td class="desktop"><?php echo $row['date']; ?></td>
						<td><?php echo $row['client']; ?></td>
						<td class="desktop"><?php echo $row['truck']; ?></td>
						<td><?php echo $row['product']; ?></td>
						<td><?php echo $row['qty']; ?></td>
						<!--td><?php //echo $row['rate'] - $row['cd'] - $row['qd'] - $row['sd']; ?></td-->
						<td><?php echo $row['bill']; ?></td>
						<td><?php echo $row['name']; ?></td>
						<td class="desktop"><?php echo $row['phone']; ?></td>
						<td><?php echo $row['remarks']; ?></td>
					</tr>																																	<?php				
				}																																		?>
			</table>
		</form>
		<br><br><br>
		<script src="bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
	</body>
</html>																																					<?php
}
else
	header("Location:../index.php");																													?>