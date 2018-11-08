<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

require 'connect.php';
  
if(count($_POST)>0) 
{
	$originalDate = $_POST["entryDate"];
	$newDate = date("Y-m-d", strtotime($originalDate)); 

			
	$result1 = mysqli_query($con,"SELECT * FROM sales_entry WHERE sales_id='" . $_GET["sales_id"] . "'");
	$row1= mysqli_fetch_array($result1,MYSQLI_ASSOC);
	
$query = mysqli_query($con,"UPDATE sales_entry SET entry_date='$newDate', ar='" . $_POST["ar"] . "', truck_no='" . $_POST["truck"] . "',
							cement='" . $_POST["cement"] . "', qty='" . $_POST["qty"] . "' ,
							remarks='" . $_POST["remarks"] . "', bill_no='" . $_POST["bill"] . "', address1='" . $_POST["address1"] . "', 
							address2='" . $_POST["address2"] . "', customer_name='" . $_POST["customerName"] . "', 
							customer_phone='" . $_POST["customerPhone"] . "'
							WHERE sales_id='" . $_GET["sales_id"] . "'");
if ( false===$query ) 
{
  printf("error: %s\n", mysqli_error($con));
}
  else{
	  $url = 'list_today_sales.php?ar=all';
	  header( "Location: $url" );
      }
}


$result = mysqli_query($con,"SELECT * FROM sales_entry WHERE sales_id='" . $_GET["sales_id"] . "'");
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);


if ( false===$result ) 
{
  printf("error: %s\n", mysqli_error($con));
}
?>

<html>
<head>
<title>Edit Sale <?php echo $row['sales_id']; ?></title>
<link rel="stylesheet" type="text/css" href="css/newEdit.css" />
</head>
<body>
<form name="frmUser" method="post" action="">
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="index.php" class="link"><img alt='Home' title='Home' src='images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="entryPage.php" class="link"><img alt='Add' title='Add' src='images/addNew.png' width='50px' height='50px'/></a>&nbsp;
	<a href="list_today_sales.php?ar=all" class="link"><img alt='List' title='List' src='images/list_icon.jpg' width='50px' height='50px'/></a>		
</div>

<br>
<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">Edit Sale <?php echo $row['sales_id']; ?> </font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td><input type="hidden" name="entryDate" class="txtField" value="<?php echo $row['sales_id']; ?>"><input type="text" name="entryDate" class="txtField" 
	value="<?php 
			$originalDate1 = $row['entry_date'];
			$newDate1 = date("d-m-Y", strtotime($originalDate1));
			echo $newDate1; ?>">
</td>

<td><label>Customer Phone</label></td>
<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $row['customer_phone']; ?>"></td>

</tr>
<tr>
<td><label>AR</label></td>
<td><input type="text" readonly name="ar" class="txtField" value="<?php echo $row['ar']; ?>"></td>

<td><label>Bill No </label></td>
<td><input type="text" name="bill" class="txtField" value="<?php echo $row['bill_no']; ?>"></td>
</tr>

<td><label>Truck No </label></td>
<td><input type="text" name="truck" class="txtField" value="<?php echo $row['truck_no']; ?>"></td>


<td><label>Customer Name</label></td>
<td><input type="text" name="customerName" class="txtField" value="<?php echo $row['customer_name']; ?>"></td>
</tr>

<td><label>CEMENT</label></td>
<td><input type="text" name="cement" readonly class="txtField" value="<?php echo $row['cement'];?>">
</td>


<td><label>Address Part 1</label></td>
<td><input type="text" name="address1" class="txtField" value="<?php echo $row['address1']; ?>"></td>
</tr>


<td><label>QUANTITY</label></td>
<td><input type="text" name="qty" class="txtField" value="<?php echo $row['qty']; ?>"></td>


<td><label>Address Part 2</label></td>
<td><input type="text" name="address2" class="txtField" value="<?php echo $row['address2']; ?>"></td>
</tr>




<td><label>Remarks</label></td>
<td><input type="text" name="remarks" class="txtField" value="<?php echo $row['remarks']; ?>"></td>
</tr>

<tr>
<td colspan="4" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
</tr>

</table>
</div>
</form>
</body></html>

<?php
}
else
header("Location:loginPage.php");
?>