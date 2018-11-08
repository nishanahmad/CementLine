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
        $query = mysqli_query($con,"UPDATE cement_details SET cement_name='" . $_POST["cement_name"] . "'
							WHERE cement_id='" . $_GET["cement_id"] . "'");
if ( false===$query ) 
{
  printf("error: %s\n", mysqli_error($con));
}
  else{
	  $url = 'cementList.php';
	  header( "Location: $url" );
      }
}


$result = mysqli_query($con,"SELECT * FROM cement_details WHERE cement_id='" . $_GET["cement_id"] . "'");
$row= mysqli_fetch_array($result,MYSQLI_ASSOC);


if ( false===$result ) 
{
  printf("error: %s\n", mysqli_error($con));
}
?>

<html>
<head>
<title>Edit Cement <?php echo $row['cement_name']; ?></title>
<link rel="stylesheet" type="text/css" href="css/newEdit.css" />
</head>
<body>
<form name="frmUser" method="post" action="">
<div style="width:100%;">

<div align="center" style="padding-bottom:5px;">
	<a href="index.php" class="link"><img alt='Home' title='Home' src='images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
	<a href="cementList.php" class="link"><img alt='List' title='List' src='images/list_icon.jpg' width='50px' height='50px'/></a>
</div>

<br>
<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="4"><div align ="center"><b><font size="4">Edit Cement <?php echo $row['cement_name']; ?> </font><b></td>
</tr>

<td><label>CEMENT NAME</label></td>
<td><input type="text" name="cement_name" class="txtField" value="<?php echo $row['cement_name']; ?>"></td>

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