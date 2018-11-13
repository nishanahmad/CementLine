<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';

	$brands = mysqli_query($con, "SELECT * FROM brands order by name ASC" ) or die(mysqli_error($con));		  
	$id = $_GET['id'];  

	if(count($_POST)>0) 
	{
		$name = $_POST['name'];
		$brand = (int)$_POST['brand'];
		
		$query = mysqli_query($con,"UPDATE products SET name='$name',brand = $brand WHERE id=$id ") or die(mysqli_error($con));				 	 

		header( "Location: list.php" );
	}

	$result = mysqli_query($con,"SELECT * FROM products WHERE id=$id ");
	$row= mysqli_fetch_array($result,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 											?>
<html>
<head>
<title>Edit Product</title>
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
</head>
<body>
<form name="frmUser" method="post" action="">
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="list.php" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
</div>

<br>
<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">Edit Product <?php echo $row['name']; ?> </font><b></td>
</tr>

<td><label>Product Name</label></td>
<td><input type="text" name="name" class="txtField" value="<?php echo $row['name']; ?>"></td>
</tr>

<td><label>BRAND</label></td>
<td><select required name="brand" class="txtField">																		<?php
	foreach($brands as $brand)																							 
	{
		if($brand['id'] == $row['brand'])
		{																												?>
			<option selected value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>					<?php		
		}
		else
		{																												?>
			<option value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>							<?php		
		}				
	}																													?>		
	</select>
</td>
</tr>

<tr>
<td colspan="2" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
</tr>

</table>
</div>
</form>
</body></html>

<?php
}
else
header("Location:../index.php");
?>