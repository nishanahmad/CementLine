<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require 'connect.php';
?>

<html>
<head>
<title>CEMENT LINE SALES ENTRY</title>

<meta charset="utf-8">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);

});
</script>
<link rel="stylesheet" type="text/css" href="css/newEdit.css" />
</head>
<body>
<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;
?>
<form name="frm" method="post" action="insertSales.php" onsubmit="return validateForm()">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="index.php" class="link"><img alt='home' title='home' src='images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="list_today_sales.php?ar=all" class="link">
<img alt='List' title='List Sales' src='images/list_icon.jpg' width='50px' height='50px'/></a>
</div>
<br>
<table border="0" cellpadding="15" cellspacing="0" width="80%" align="center" style="float:center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">ADD NEW SALES ENTRY </font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td><input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date('d-m-Y'); ?>" /></td>

<td><label>Customer Phone</label></td>
<td><input type="text" name="customerPhone" class="txtField"></td>
</tr>

<td><label>AR</label></td>
<td><select required name="ar" class="txtField">
    <option value = "">---Select---</option>
    <?php
    $queryusers = "SELECT ar_name FROM ar_details ORDER BY ar_name";
    $db = mysqli_query($con,$queryusers);
    while ( $d=mysqli_fetch_assoc($db)) {
     echo "<option value='".$d['ar_name']."'>".$d['ar_name']."</option>";    }
    ?>
      </select>
</td>

<td><label>Bill No</label></td>
<td><input type="text" name="bill" class="txtField"></td>
</tr>

<td><label>Truck no</label></td>
<td><input type="text" name="truck" class="txtField"></td>


<td><label>Customer Name</label></td>
<td><input type="text" name="customerName" class="txtField"></td>
</tr>

<td><label>CEMENT</label></td>
<td><select required name="cement" class="txtField">
    <option value = "">---Select---</option>
    <?php
    $queryusers = "SELECT `cement_name` FROM `cement_details` ";
    $db = mysqli_query($con,$queryusers);
    while ( $d=mysqli_fetch_assoc($db)) {
     echo "<option value='".$d['cement_name']."'>".$d['cement_name']."</option>";    }
    ?>
      </select>
</td>



<td><label>Address Part 1</label></td>
<td><input type="text" name="address1" class="txtField"></td>
</tr>


<td><label>QUANTITY</label></td>
<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" title="Input a valid number"></td>


<td><label>Address Part 2</label></td>
<td><input type="text" name="address2" class="txtField"></td>
</tr>




<td><label>Remarks</label></td>
<td><input type="text" name="remarks" class="txtField"></td>




</tr>

</tr>

<tr>

<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
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