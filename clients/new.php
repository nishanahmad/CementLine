<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
<head>
<title>INSERT NEW AR</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
</head>
<body>
<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;
?>
<form name="frm" method="post" action="insert.php" >
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="list.php" class="link">
<img alt='List' title='List Ar' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
</div>
<br>

<table border="0" cellpadding="15" cellspacing="0" width="50%" align="center" style="float:center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="2"><div align ="center"><b><font size="4">ADD NEW AR </font><b></td>
</tr>

<td><label>AR NAME</label></td>
<td><input type="text" name="name" class="txtField"></td>
</tr>

<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
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