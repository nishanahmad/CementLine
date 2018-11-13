<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
  
	if(isset($_GET['from']))
		$fromDate = date("Y-m-d", strtotime($_GET['from']));		
	else
		$fromDate = date("Y-m-d");		

	if(isset($_GET['to']))		
		$toDate = date("Y-m-d", strtotime($_GET['to']));		
	else
		$toDate = date("Y-m-d");		
	
	if(isset($_GET['brand']))
		$urlBrand = (int)$_GET['brand'];
	else
		$urlBrand = 1;
	
	$arObjects = mysqli_query($con, "SELECT * FROM clients order by name ASC" ) or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['name'];
		$arShopMap[$ar['id']] = $ar['shop'];
		$arPhoneMap[$ar['id']] = $ar['mobile'];
	}
	
	$brands = mysqli_query($con, "SELECT * FROM brands order by name ASC" ) or die(mysqli_error($con));		
	
	if($_POST)
	{
		$from = $_POST['fromDate'];
		$to = $_POST['toDate'];
		$brand = $_POST['brand'];
		
		header("Location:totalSalesAR.php?from=$from&to=$to&brand=$brand");	
	}	
?>
<html>
<head>
	<title>AR Sale Date Wise</title>
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/responstable.css">
	<link rel="stylesheet" type="text/css" href="../css/glow_box.css">	
	<link rel="stylesheet" href="../css/greenButton.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">			
	
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery.floatThead.min.js"></script>
	<script>
	$(function() {
		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
		$( "#fromDate" ).datepicker(pickerOpts);
		
		var pickerOpts2 = { dateFormat:"dd-mm-yy"}; 
		$( "#toDate" ).datepicker(pickerOpts2);		

	});
	
	$(document).ready(function() {	
		var $table = $('.responstable');
		$table.floatThead();	
	});		

	</script>	
</head>
<body>
<div align="center">
<br><br>
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<br><br><br><br>
<form method="post" action="" autocomplete="off">
	<input type="text" id="fromDate" class="textarea" name="fromDate" required value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>" />
	&nbsp;&nbsp;to&nbsp;&nbsp;
	<input type="text" id="toDate" class="textarea" name="toDate" required value="<?php echo date('d-m-Y',strtotime($toDate)); ?>" />
		&nbsp;&nbsp;&nbsp;&nbsp;
		<select required name="brand" class="textarea"><?php
		foreach($brands as $brand) 
		{	
			if($urlBrand == $brand['id'])
			{																												?>
				<option selected value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>					<?php
			}
			else
			{																												?>
				<option value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>					<?php
			}				
		}																													?>
		</select>
	<br><br>
	<input type="submit" class="btn" name="submit" value="Search">	
</form>
<br>
<table class="responstable" name="responstable" id="responstable" style="width:50%;">
<thead>
	<tr>
		<th style="text-align:left;">AR</th>
		<th style="text-align:left;">Shop</th>	
		<th>Phone</th>
		<th>Qty</th>
	</tr>
</thead>
<?php
	$products = mysqli_query($con, "SELECT * FROM products WHERE brand = $urlBrand" ) or die(mysqli_error($con));
	foreach($products as $product)
	{
		$productMap[$product['id']] = $product['name'];
	}
	$productIds = implode("','",array_keys($productMap));
	
	$salesList = mysqli_query($con, "SELECT client,SUM(qty) FROM sales WHERE date >= '$fromDate' AND date <= '$toDate' AND product IN('$productIds') GROUP BY client" ) or die(mysqli_error($con));
	$total = 0;
	foreach($salesList as $arSale)
	{
?>		<tr>
			<td style="text-align:left;min-width:120px;"><?php echo $arNameMap[$arSale['client']];?></td>
			<td style="text-align:left;min-width:100px;"><?php echo $arShopMap[$arSale['client']];?></td>			
			<td><?php echo $arPhoneMap[$arSale['client']];?></td>
			<td><?php echo $arSale['SUM(qty)'];?></td>
		</tr>
<?php	
		$total = $total + $arSale['SUM(qty)'];
	}
?>	
	<tr>
		<td colspan="8"></td>
	</tr>
	<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
		<td colspan="3" style="text-align:right">TOTAL</td>
		<td><?php echo $total;?></td>
	</tr>
</table>
<br><br><br><br><br><br>
</div>
</body>			
<?php
}
else
	header("Location:../index.php");	
?>