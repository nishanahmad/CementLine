<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	echo "LOGGED USER : ".$_SESSION["user_name"] ;	

	require '../connect.php';
	require '../functions/rate.php';

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

	$rates = mysqli_query($con,"SELECT * FROM rate INNER JOIN products ON rate.product = products.id ORDER BY date DESC,products.name ASC") or die(mysqli_error($con));
	foreach($rates as $rate)
	{
		if(!isset($rateMap[$rate['product']]))
			$rateMap[$rate['product']] = $rate['rate'];
	}	
	
	$companyRates = mysqli_query($con,"SELECT * FROM company_rate INNER JOIN products ON company_rate.product = products.id ORDER BY date DESC,products.name ASC") or die(mysqli_error($con));
	foreach($companyRates as $cRate)
	{
		if(!isset($companyRateMap[$cRate['product']]))
		{
			$companyRateMap[$cRate['product']]['rate'] = $cRate['rate'];
			$companyRateMap[$cRate['product']]['recommended'] = $cRate['recommended'];
		}
	}		
	
	$discountMap = array();
	$discounts = mysqli_query($con,"SELECT * FROM discounts WHERE type = 'wd' AND date = CURDATE()") or die(mysqli_error($con));
	foreach($discounts as $discount)
	{
		$discountMap[$discount['product']] = $discount['amount'];
	}		
		
	$i=0;
	$productMap = array();
	$mainMap = array();
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
		$mainMap[$i]['date'] = $row['date'];
		$mainMap[$i]['client'] = $clientMap[$row['client']];
		$mainMap[$i]['clientId'] = $row['client'];
		$mainMap[$i]['truck'] = $row['truck_no'];
		$mainMap[$i]['productId'] = $row['product'];
		$mainMap[$i]['product'] = $productNameMap[$row['product']];
		$mainMap[$i]['qty'] = $row['qty'];
		$mainMap[$i]['discount'] = $row['discount'];
		$mainMap[$i]['bill'] = $row['bill_no'];
		$mainMap[$i]['name'] = $row['customer_name'];
		$mainMap[$i]['phone'] = $row['customer_phone'];
		$mainMap[$i]['remarks'] = $row['remarks'];
		
		$i++;
	}	
		?>
<html>
	<style>
		@media only screen and (max-width: 900px) {
			.desktop{
				display: none;
			}	

		.rateTable{
			width:50%;
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
					<th>Rate</th>
					<th style="width:20%;">Discount</th>									
					<th>Product</th>
					<th>Qty</th>
					<th style="border-color:white black white black;width:7%;"></th>
					<th style="width:15%;">Company Rate</th>
					<th style="width:15%;">Company Recommended</th>					
				</tr>			
			<?php				
			foreach($rateMap as $product=>$rate)
			{																																		?>
				<tr>
					<td><?php echo $rate.'/-';?></td>						
					<td><?php if(isset($discountMap[$product])) echo $discountMap[$product].'/-';?></td>										
					<td><?php echo $productNameMap[$product];?></td>
					<td><?php if(isset($productMap[$product])) echo $productMap[$product];?></td>						
					<td style="border-color:white black white black;"></td>
					<td><?php if(isset($companyRateMap[$product])) echo $companyRateMap[$product]['rate'].'/-';?></td>						
					<td><?php if(isset($companyRateMap[$product])) echo $companyRateMap[$product]['recommended'].'/-';?></td>						
				</tr>
								<?php
			}?> 
				<tr>
					<th></th>						
					<th></th>										
					<th>Total</th>
					<th><?php echo $total;?></th>
					<th style="border-color:white black white black;"></th>
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
					<td>RATE</td>
					<td>PRODUCT</td>
					<td>QTY</td>
					<td>BILL NO</td>
					<td>CUST. NAME</td>
					<td class="desktop">CUST. PHONE</td>
					<td>REMARKS</td>
				</tr>																																															<?php
				foreach($mainMap as $index => $row) 
				{
					$rowRate = getRate($row['date'],$row['productId']);
					if($rowRate == null)
						$rowRate = 0;					
					
					$rowWD = getWD($row['date'],$row['productId']);
					if($rowWD == null)
						$rowWD = 0;
					
					$rowCD = getCD($row['date'],$row['productId'],$row['clientId']);
					if($rowCD == null)
						$rowCD = 0;					
					
					$rowSD = getSD($row['date'],$row['productId'],$row['clientId']);
					if($rowSD == null)
						$rowSD = 0;										

					$finalRate = $rowRate - $rowWD - $rowCD - $rowSD - $row['discount'];																											?>	
					<tr class="blue">
						<td class="desktop"><a href="edit.php?id=<?php echo $row['id']; ?>" class="link"><img alt='Edit' title='Edit' src='../images/edit.png' width='20px' height='20px' hspace='10' /></a></td>  
						<td class="desktop"><?php echo date('d-m-Y',strtotime($row['date'])); ?></td>
						<td><?php echo $row['client']; ?></td>
						<td class="desktop"><?php echo $row['truck']; ?></td>
						<td><?php echo $finalRate.'/-';?></td>
						<td><?php echo $row['product']; ?></td>
						<td><?php echo $row['qty']; ?></td>
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