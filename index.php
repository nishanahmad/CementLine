<?php
session_start();
if(isset($_SESSION["user_name"]))
{																						?>
<html>
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<head>
<title>HOME</title>
<link rel="stylesheet" type="text/css" href="css/index.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="background">
</div>
<div class="container">
  <div class="row">
    <h1>CEMENT LINE</h1>
  </div>
  <hr/>
</div>
   
<br><br>

	<div class="row">
	
	<a href="sales/todayList.php?client=all" class="btn lg ghost">LIST TODAY SALES</a>
    <br><br><br>

	<a href="sales/list.php" class="btn lg ghost">LIST ALL SALES</a>
    <br><br><br><br>
		
   	<a href="clients/list.php" class="btn lg ghost">CLIENTS</a>
    <br><br><br>	
	
   	<a href="Target/" class="btn lg ghost">TARGET & POINTS</a>
    <br><br><br>		
	
   	<a href="products/list.php" class="btn lg ghost">PRODUCTS</a>
    <br><br><br>																																		<?php
	
	if($_SESSION["user_name"] == 'MANJUSHA' || $_SESSION["user_name"] == 'nishan')
	{																																					?>
		<a href="discounts/" class="btn lg ghost">RATE & DISCOUNTS</a>
		<br><br><br>																																	<?php	
	}																																					?>	


	
	<a href="reports/" class="btn lg ghost">REPORTS</a>
    <br><br><br>

	</div>
</body>
</html>
<?php
}
else
	header("Location:loginPage.php");
?>