<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>
<html>
<head>
<style type="text/css">
a{
  text-decoration:none;
}
</style>
<title>Rate & Discounts</title>
<link rel="stylesheet" type="text/css" href="../css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
  <div class="row">
	<a href="../index.php"><img alt='Add' title='Add New' src='../images/homeSilver.png' width='80px' height='80px'/></a>
  </div>
  <hr />
  </div>

  <div class="row">
  <h1>Rate & Discounts</h1>
  <br><br> 
   	<a href="../rate/list.php" class="btn lg ghost">Rate List</a>
    <br><br><br>	
   	<a href="../company_rate/list.php" class="btn lg ghost">Company Rate List</a>
    <br><br><br>		
   	<a href="list.php?status=1" class="btn lg ghost">Discount List</a>
    <br><br><br>		
	
	</div>

</div>
</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>