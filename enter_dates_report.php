<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
<head >
<link rel="stylesheet" type="text/css" href="css/enter_dates.css" />
<meta charset="utf-8">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker1" ).datepicker(pickerOpts);

});

$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker2" ).datepicker(pickerOpts);

});

</script>

</head>
 
<body>
<div class="background" align = "center">
<a href="index.php" class="link"><img alt='home' title='home' src='images/homeSilver.png' width='100px' height='100px'/> </a>
<br><br><br><br><br>
<form name="frmdate" method="post" action="generate_report.php">

<table border="0" cellpadding="1" cellspacing="10" width="25%" align="center">
<tr>
<td><b><font color="#989898">FROM :</font></b></td>
<b></b>
<td>
<input type="date" id="datepicker1" name="from" size="20" placeholder="From date" />
</td>
</tr>
<tr></tr><tr></tr>
<tr> 
<td><b><font color="#989898">TO :</font></b></td>
<b></b>
<td>
<input type="date" id="datepicker2" name="to" size="20" placeholder="To date"/>
</td>
</tr>
<tr></tr><tr></tr><tr></tr>
<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Generate" ></div></td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>