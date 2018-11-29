<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$clients = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name ASC");
	$products= mysqli_query($con,"SELECT id,name FROM products WHERE isActive = 1 ORDER BY name ASC");
?>

<html>
<head>
<title>New Sale</title>

<meta charset="utf-8">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script>
$(function() {
	var pickerOpts = { dateFormat:"d-mm-yy"}; 
	$( "#datepicker" ).datepicker(pickerOpts);

	$("#datepicker").change(function(){
		var product = $("#product").val();
		$.ajax({
			type: "POST",
			url: "getRate.php",
			data:'date='+$(this).val()+'&product='+product,
			success: function(data){
				$("#rate").val(data);
				refreshRate();
			}
		});
	});
	
	$("#product").change(function(){
		var date = $("#datepicker").val();
		$.ajax({
			type: "POST",
			url: "getRate.php",
			data:'product='+$(this).val()+'&date='+date,
			success: function(data){
				$("#rate").val(data);
				refreshRate();
			}
		});
	});
});

function refreshRate()
{
	var rate=document.getElementById("rate").value;
	var cd=document.getElementById("cd").value;
	var qd=document.getElementById("qd").value;
	var sd=document.getElementById("sd").value;
	
	$('#final').val(rate-cd-qd-sd);
}

</script>
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
</head>
<body>
<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;
?>
<form name="frm" method="post" action="insert.php" onsubmit="return validateForm()">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="todayList.php?client=all" class="link">
	<img alt='List' title='List Sales' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
</div>
<br>
<table border="0" cellpadding="15" cellspacing="0" width="80%" align="center" style="float:center" class="tblSaveForm">
	<tr class="tableheader">
		<td colspan="4"><div align ="center"><b><font size="4">NEW SALE </font><b></td>
	</tr>

	<tr>
		<td><label>Date</label></td>
		<td><input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date('d-m-Y'); ?>" /></td>

		<td><label>Bill No</label></td>
		<td><input type="text" name="bill" class="txtField"></td>
	</tr>

	<tr>
		<td><label>AR</label></td>
		<td><select required name="client" class="txtField">
				<option value = "">---Select---</option>																			<?php
				foreach($clients as $client) 
				{																													?>
					<option value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>										<?php	
				}																													?>
			</select>
		</td>

		<td><label>Truck no</label></td>
		<td><input type="text" name="truck" class="txtField"></td>
	</tr>
	
	<tr>
		<td><label>Product</label></td>
		<td><select required name="product" id="product" class="txtField">
				<option value = "">---Select---</option>																			<?php
				foreach($products as $product) 
				{																													?>
					<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>										<?php	
				}																													?>
			</select>
		</td>

		<td><label>Customer Phone</label></td>
		<td><input type="text" name="customerPhone" class="txtField"></td>
	</tr>

	<tr>
		<td><label>Quantity</label></td>
		<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" title="Input a valid number"></td>

		<td><label>Customer Name</label></td>
		<td><input type="text" name="customerName" class="txtField"></td>
	</tr>

	<tr>
		<td><label>Rate</label></td>
		<td><input type="text" readonly name="rate" class="txtField" id="rate" onchange="refreshRate();"></td>

		<td><label>Address Part 1</label></td>
		<td><input type="text" name="address1" class="txtField"></td>
	</tr>

	<tr>
		<td><label>Cash Discount</label></td>
		<td><input type="text" name="cd" class="txtField" id="cd" onchange="refreshRate();"></td>	

		<td><label>Address Part 2</label></td>
		<td><input type="text" name="address2" class="txtField"></td>	
	</tr>
	
	<tr>
		<td><label>Special Discount</label></td>
		<td><input type="text" name="sd" class="txtField" id="qd" onchange="refreshRate();"></td>	

		<td><label>Remarks</label></td>
		<td><input type="text" name="remarks" class="txtField"></td>
	</tr>

	<tr>
		<td><label>Wagon Discount</label></td>
		<td><input type="text" readonly name="wd" class="txtField" id="sd" onchange="refreshRate();"></td>	
		<td></td>
		<td></td>
	</tr>

	<tr>
		<td><label>Final Rate</label></td>
		<td><input readonly type="text" class="txtField" id="final"></td>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
	</tr>
</table>
</div>
</form>
</body>
</html>

<?php
}
else
	header("Location:../index.php");
?>