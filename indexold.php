<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>


<div class="background">
</div>
<div class="container">
  <div class="row">
    <h1><img alt='Add' title='Add New' src='images/logo.png' width='300px' height='50px'/></h1>
    <h4></h4>
  </div>
  <hr />
  </div>
  
 
<br><br> 

  <div class="row">
    
	<button  class="btn lg ghost" onclick="location.href='entryPage.php'"><b>ADD NEW SALES</b></button>
    <br><br><br>

	<button  class="btn lg ghost" onclick="location.href='list_today_sales.php?ar=all'"><b>LIST TODAY'S SALES</b></button>
    <br><br><br>


	<button  class="btn lg ghost" onclick="location.href='list_all_sales.php?ar=all'"><b>LIST ALL SALES</b></button>
    <br><br><br><br>

   	<button  class="btn lg ghost" onclick="location.href='arList.php'"><b>AR LIST</b></button>
    <br><br><br>

	
	<button  class="btn lg ghost" onclick="location.href='reportpage.php'"><b>REPORTS</b></button>
    
  </div>

</div>
</body>
</html>
<?php
}
else
header("Location:loginPage.php");
?>