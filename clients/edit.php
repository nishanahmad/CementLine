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
	
	$name = $_POST["name"];
	$query = mysqli_query($con,"UPDATE clients SET name='$name' WHERE id=$id ") or die(mysqli_error($con));				 	 

	header( "Location: list.php" );
}

$result = mysqli_query($con,"SELECT * FROM clients WHERE id=$id ");
$row= mysqli_fetch_array($result,MYSQLI_ASSOC) or die(mysqli_error($con));				 	 											?>

<html>
<head>
<title>Edit Sale <?php echo $row['sales_id']; ?></title>
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
<td colspan="4"><div align ="center"><b><font size="4">Edit AR <?php echo $row['name']; ?> </font><b></td>
</tr>

<td><label>AR NAME</label></td>
<td><input type="text" name="name" class="txtField" value="<?php echo $row['name']; ?>"></td>

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
header("Location:loginPage.php");
?>