<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';
	require 'insertNewMonthPoints.php'; 
	
	$year = $_GET['year'];
	$month = $_GET['month'];

	$arObjects = mysqli_query($con, "SELECT * FROM clients WHERE isActive = 1 ORDER BY name asc") or die(mysqli_error($con).' LINE 13');
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']] = $ar['name'];
	}
	
	$array = implode("','",array_keys($arMap));	
	$sql = "SELECT * FROM target WHERE year='$year' AND Month='$month' AND client IN ('$array')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con).' LINE 21');		

	if(mysqli_num_rows($result) > 0)
	{
?>  	<html>
		<div align="center" style="font-size:40px"><br><br>Data already genrated for the month you selected. Go to update page to update the values
		<br><br>
		<button onclick="window.location.href='generateDateSelectPage.php'">Click here to go back</button>
		</div>
<?php
	exit;	
	}
	else
	{
		insertNewMonthPoints($month,$year);
		
		$sql = "SELECT * FROM target WHERE year='$year' AND Month='$month'  AND client IN ('$array')";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));				
	}	
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/responstable.css" rel="stylesheet">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<title>New Target</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
<br><br>
<font size="5px"><b><?php echo $year;?></b></font>
<br>
</div>
<br><br>
<form name="arBulkUpdate" method="post" action="updateServer.php">
	<table align="center" class="responstable" style="width:50%;">
		<tr>
			<th style="width:40%">Client Name</th>
			<th style="width:20%;text-align:center;">Target</th>
			<th style="width:20%;text-align:center;">Rate</th>
			<th style="width:20%;text-align:center;">Payment %</th> 
		</tr>																												<?php
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$arId = $row['client'];
			$target = $row['target'];
			$rate = $row['rate'];
			$pp = $row['payment_perc'];																						?>				
		<tr>
			<td><label align="center"><?php echo $arMap[$arId]; ?></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-target';?>" value="<?php echo $target; ?>"></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-rate';?>" value="<?php echo $rate; ?>"></td>		
			<td style="text-align:center;"><input type="text" style="text-align:center;width:70px;border:0px;background-color: transparent;" name="<?php echo $arId.'-pp';?>" value="<?php echo $pp; ?>"></td>		
		</tr>																												<?php
		}																														?>
		<input type="hidden" name="year" value="<?php echo $year;?>">
		<input type="hidden" name="month" value="<?php echo $month;?>">
	</table>
	<br><br>
		<div align="center"><input type="submit" name="submit" value="Submit"></div>																																	
	<br><br>  
</body>
</html>																														<?php
}
else
	header("../Location:index.php");

?>