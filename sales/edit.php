<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	echo "LOGGED USER : ".$_SESSION["user_name"] ;	

	require '../connect.php';

	$id = $_GET["id"];  

	$query = mysqli_query($con,"SELECT * FROM sales WHERE id = $id ");
	$sale= mysqli_fetch_array($query,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
	
	$date = $sale['date'];
	$product = $sale['product'];
	
	$rateQuery = mysqli_query($con,"SELECT * FROM rate WHERE date <= '$date' AND product = '$product' ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($rateQuery) >0)
		$rate= mysqli_fetch_array($rateQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
	else
		$rate['rate'] = null;
	
	$wdQuery = mysqli_query($con,"SELECT * FROM discounts WHERE date = '$date' AND product = '$product' AND type='wd' ") or die(mysqli_error($con));				 	 
	if(mysqli_num_rows($wdQuery) >0)
		$wd= mysqli_fetch_array($wdQuery,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 
	else
		$wd['amount'] = null;	

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
	
	$finalRate = $rate['rate'] - $wd['amount'];
	?>
<html>
<head>
<title>Edit Sale <?php echo $sale['id']; ?></title>
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/button.css">
</head>
<body>
<form name="frmUser" method="post" action="update.php" autocomplete="off">
<input type="hidden" name="id" value="<?php echo $sale['id'];?>"/>
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="new.php" class="link"><img alt='Add' title='Add' src='../images/addnew.png' width='50px' height='50px'/></a>&nbsp;
	<a href="todayList.php?client=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>		
</div>

<br>
<table border="0" cellpadding="15" cellspacing="0" width="80%" align="center" style="float:center" class="tblSaveForm">
	<tr class="tableheader">
		<td colspan="4"><div align ="center"><b><font size="4">Edit Sale </font><b></td>
	</tr>

	<tr>
		<td><label>Date</label></td>
		<td><input type="text" readonly  class="txtField" name="date" required value="<?php echo date('d-m-Y',strtotime($sale['date'])); ?>" /></td>

		<td><label>Bill No</label></td>
		<td><input type="text" name="bill" class="txtField" value="<?php echo $sale['bill_no'];?>"></td>
	</tr>

	<tr>
		<td><label>AR</label></td>
		<td><select disabled required name="client" class="txtField">
				<option value = "">---Select---</option>																			<?php
				foreach($clients as $client) 
				{																													
					if($sale['client'] == $client['id'])
					{																												?>
						<option selected value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>										<?php								
					}
					else
					{																												?>
						<option value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>										<?php		
					}
				}																													?>
			</select>
		</td>

		<td><label>Truck no</label></td>
		<td><input type="text" name="truck" class="txtField" value="<?php echo $sale['truck_no'];?>"></td>
	</tr>
	
	<tr>
		<td><label>Product</label></td>
		<td><select disabled required name="product" id="product" class="txtField">
				<option value = "">---Select---</option>																			<?php
				foreach($products as $product) 
				{
					if($sale['product'] == $product['id'])
					{																												?>
						<option selected value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>										<?php								
					}
					else
					{																												?>
						<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>										<?php		
					}
				}																													?>
			</select>
		</td>

		<td><label>Customer Phone</label></td>
		<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $sale['customer_phone'];?>"></td>
	</tr>

	<tr>
		<td><label>Quantity</label></td>
		<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" title="Input a valid number" value="<?php echo $sale['qty'];?>"></td>

		<td><label>Customer Name</label></td>
		<td><input type="text" name="customerName" class="txtField" value="<?php echo $sale['customer_name'];?>"></td>
	</tr>

	<tr>
		<td><label>Rate</label></td>
		<td><input type="text" readonly name="rate" class="txtField" id="rate" value="<?php echo $rate['rate'];?>" onchange="refreshRate();"></td>

		<td><label>Address Part 1</label></td>
		<td><input type="text" name="address1" class="txtField" value="<?php echo $sale['address1'];?>"></td>
	</tr>

	<tr>
		<td><label>Cash Discount</label></td>
		<td><input type="text" readonly name="cd" class="txtField" id="cd"  onchange="refreshRate();"></td>	

		<td><label>Address Part 2</label></td>
		<td><input type="text" name="address2" class="txtField" value="<?php echo $sale['address2'];?>"></td>
	</tr>
	
	<tr>
		<td><label>Special Discount</label></td>
		<td><input type="text" readonly name="sd" class="txtField" id="sd"  onchange="refreshRate();"></td>	

		<td><label>Remarks</label></td>
		<td><input type="text" name="remarks" class="txtField" value="<?php echo $sale['remarks'];?>"></td>
	</tr>

	<tr>
		<td><label>Wagon Discount</label></td>
		<td><input type="text" readonly name="wd" class="txtField" id="wd" value="<?php echo $wd['amount'];?>"></td>	
		<td></td>
		<td></td>
	</tr>

	<tr>
		<td><label>Final Rate</label></td>
		<td><input readonly type="text" class="txtField" id="final" value="<?php echo $finalRate;?>"></td>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
	</tr>
</table>

<br><br>
<a href="delete.php?id=<?php echo $sale['id'];?>" style="float:right;width:50px;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>				
<br><br>	
</div>
</form>
</body>
</html>

<?php
}
else
	header("Location:../index.php");
?>