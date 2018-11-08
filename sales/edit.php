<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

require '../connect.php';

$id = $_GET["id"];  

if(count($_POST)>0) 
{
	$date = $_POST['date'];
	$sqlDate = date("Y-m-d", strtotime($date));
	$truck = $_POST['truck'];
	$qty = $_POST['qty'];
	$remarks = $_POST['remarks'];
	$bill = $_POST['bill'];
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	
	$update = mysqli_query($con,"UPDATE sales SET date='$sqlDate', truck_no='$truck', qty='$qty' ,
								 remarks='$remarks', bill_no='$bill', 
								 address1='$address1', address2='$address2', customer_name='$customerName', 
								 customer_phone='$customerPhone'
								 WHERE id = $id") or die(mysqli_error($con));				 	 

	  $url = 'todayList.php?client=all';
	  header( "Location: $url" );
}


$query = mysqli_query($con,"SELECT * FROM sales WHERE id = $id ");
$sale= mysqli_fetch_array($query,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 

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

?>

<html>
<head>
<title>Edit Sale <?php echo $sale['id']; ?></title>
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/button.css">
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);

});
</script>
</head>
<body>
<form name="frmUser" method="post" action="" autocomplete="off">
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="new.php" class="link"><img alt='Add' title='Add' src='../images/addNew.png' width='50px' height='50px'/></a>&nbsp;
	<a href="todayList.php?client=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>		
</div>

<br>
<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">Edit Sale <?php echo $sale['id']; ?> </font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td><input type="text" name="date" class="txtField" id="datepicker" value="<?php echo date('d-m-Y', strtotime($sale['date']));?>"/>
</td>

<td><label>Customer Phone</label></td>
<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $sale['customer_phone']; ?>"></td>

</tr>
<tr>
<td><label>AR</label></td>
<td><input type="text" readonly name="client" class="txtField" value="<?php echo $clientMap[$sale['client']]; ?>"></td>

<td><label>Bill No </label></td>
<td><input type="text" name="bill" class="txtField" value="<?php echo $sale['bill_no']; ?>"></td>
</tr>

<td><label>Truck No </label></td>
<td><input type="text" name="truck" class="txtField" value="<?php echo $sale['truck_no']; ?>"></td>


<td><label>Customer Name</label></td>
<td><input type="text" name="customerName" class="txtField" value="<?php echo $sale['customer_name']; ?>"></td>
</tr>

<td><label>Product</label></td>
<td><input type="text" name="product" readonly class="txtField" value="<?php echo $productMap[$sale['product']];?>">
</td>


<td><label>Address Part 1</label></td>
<td><input type="text" name="address1" class="txtField" value="<?php echo $sale['address1']; ?>"></td>
</tr>


<td><label>QUANTITY</label></td>
<td><input type="text" name="qty" class="txtField" value="<?php echo $sale['qty']; ?>"></td>


<td><label>Address Part 2</label></td>
<td><input type="text" name="address2" class="txtField" value="<?php echo $sale['address2']; ?>"></td>
</tr>




<td><label>Remarks</label></td>
<td><input type="text" name="remarks" class="txtField" value="<?php echo $sale['remarks']; ?>"></td>
</tr>

<tr>
<td colspan="4" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
</tr>
</table>
<br><br><br><br>	
<a href="delete.php?id=<?php echo $sale['id'];?>" style="float:right;width:50px;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>				
</div>
</form>
</body></html>

<?php
}
else
	header("Location:../index.php");
?>