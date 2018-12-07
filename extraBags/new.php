<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
	$products = mysqli_query($con,"SELECT * FROM brands INNER JOIN products ON products.brand = brands.id WHERE brands.name = 'COROMANDEL' OR brands.name = 'ZUARI' ORDER BY products.name ASC") or die(mysqli_error($con));	
	foreach($products as $product)
	{
		$productMap[$product['id']] = $product['name'];
	}

	$arObjects = mysqli_query($con,"SELECT id,name,shop FROM clients WHERE isActive = 1 ORDER BY name ASC");	
	
	// Populate maps for SAP CODE and AR NAME
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['name'];
		$shopNameMap[$ar['id']] = $ar['shop'];			
	}
	$arNameArray = json_encode($arNameMap);
	$arNameArray = str_replace('\n',' ',$arNameArray);
	$arNameArray = str_replace('\r',' ',$arNameArray);		
	
	$shopNameArray = json_encode($shopNameMap);
	$shopNameArray = str_replace('\n',' ',$shopNameArray);
	$shopNameArray = str_replace('\r',' ',$shopNameArray);	
?>

<html>
<head>
<title>EXTRA BAGS</title>
<script>
var arNameList = '<?php echo $arNameArray;?>';
var arName_array = JSON.parse(arNameList);
var arNameArray = arName_array;									

var shopNameList = '<?php echo $shopNameArray;?>';
var shopName_array = JSON.parse(shopNameList);
var shopNameArray = shopName_array;									


function arRefresh()
{
	var arId = $('#ar').val();
	var shopName = shopNameArray[arId];
	$("#shopName").text(shopName);
}								
</script>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css/companySale.css" />
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>

<script>
$(function() {

var pickerOpts = { dateFormat:"dd-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);

});
</script>

</head>
<body>
<form name="frmUser" method="post" action="insert.php" onsubmit="return validateForm()" autocomplete="off">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>
<table border="0" cellpadding="15" cellspacing="0" width=50%" align="center" style="float:center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">EXTRA BAGS</font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td colspan="3"><input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date('d-m-Y'); ?>" /></td>
</tr>
<tr>
<td><label>Client</label></td>
<td><select name="client" id="client" required class="txtField" onChange="arRefresh();">
    <option value = "">---Select---</option>
    <?php   
    foreach($arNameMap as $arId => $arName)
	{
?>		<option value="<?php echo $arId;?>"><?php echo $arName;?></option>			<?php
	}
?>
      </select>
</td>
</tr>
<tr>
<td><label>Product</label></td>
<td><select name="product" id="product" required class="txtField">
    <option value = "">---Select---</option>
    <?php   
    foreach($productMap as $id => $name)
	{
?>		<option value="<?php echo $id;?>"><?php echo $name;?></option>			<?php
	}
?>
      </select>
</td>
</tr>

<tr>
<td><label>Quantity</label></td>
<td colspan="3"><input type="text" name="qty" class="txtField" pattern="[0-9]+" title="Input a valid number" required></td>
</tr>
<tr>
<tr>
<td><label>Remarks</label></td>
<td colspan="3"><input type="text" name="remarks" class="txtField"></td>
</tr>

<tr>
<td colspan="4"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
</tr>

</table>

<table border="0" cellpadding="15" cellspacing="0" width=50%" align="center" style="float:center">
<tr>
	<td id="shopName"/>
</tr>
<tr>
	<td id="sapCode"/>
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