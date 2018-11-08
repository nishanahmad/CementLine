<?php
session_start();
require 'connect.php';
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	
	
	$mainarray = array(); 
	
	$result = mysqli_query($con,"SELECT cement,qty FROM sales_entry WHERE entry_date >= CURDATE() order by cement asc ");
			
		if ( false===$result ) 
		{
			printf("error: %s\n", mysqli_error($con));
		}
		
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		if(isset($mainarray[$row["cement"]]))
			$mainarray[$row["cement"]] = $mainarray[$row["cement"]] + $row["qty"];
		else
			$mainarray[$row["cement"]] = $row["qty"];

			
	}
	
	//var_dump($mainarray);
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SUM OF TODAY'S SALES</title>		
            
<link rel="stylesheet" type="text/css" href="css/table.css" />
</head>
<body>
<div align="center">
<a href="index.php" class="link"><img alt='home' title='home' src='images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="list_today_sales.php?ar=all" class="link">
<img alt='List' title='List Sales' src='images/list_icon.jpg' width='50px' height='50px'/></a>


<table class=hor-minimalist-b>
<?php foreach($mainarray as $cement=>$qty)
	  {
?>	  <tr><td><?php echo $cement ?></td><td><?php echo $qty ?></td></tr>
<?php  
	  }
?>	  
</table>

</div?
</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>