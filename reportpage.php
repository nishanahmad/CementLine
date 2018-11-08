<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>

<html>
   <head>
   <link rel="stylesheet" type="text/css" href="css/style.css">
   </head>
  
<body>
<body background="Images/bg.jpg" >
<br /><br />    
<div align="center" style="padding-bottom:5px;">
<a href="index.php" class="link"><img alt='home' title='home' src='images/homeSilver.png' width='100px' height='100px'/> </a> &nbsp;&nbsp;&nbsp;
    
<br />
<!-- <div align="center"><img src="Images/logo.png"></div>       -->
    <script type="text/javascript">

    function submitAction(act) {
         document.sample.action = act;
         document.sample.submit();

    }
    </script>
	
    <form name ="sample" action="default.php">


<div align="center">

<br /><br /><br /><br />
<input type="button" id = "list_today_sales" class ="button"  value ="AR WISE REPORT" onClick="document.location.href = 'enter_dates_report.php'">

<br /><br /><br /><br />
<input type="button" id = "list_all_sales" class ="button"  value ="CEMENT WISE REPORT" onClick="document.location.href = 'enter_dates_sum.php'">

</div>
    </form>

</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>