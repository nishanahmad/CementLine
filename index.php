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
<body background="images/bg.jpg" >
<br />
    <script type="text/javascript">

    function submitAction(act) {
         document.sample.action = act;
         document.sample.submit();

    }
    </script>
	
    <form name ="sample" action="default.php">

<br /><br /><br /><br />
<div align="center">

<input  type="button" class ="button" style="width:200px;"  value ="ADD NEW SALE" onClick="document.location.href = 'sales/new.php?client=all'">

<br /><br /><br /><br />
<input type="button" id = "list_today_sales" class ="button" style="width:200px;" value ="LIST TODAY'S SALES" onClick="document.location.href = 'sales/todayList.php?client=all'">

<br /><br /><br /><br />
<input type="button" id = "list_all_sales" class ="button" style="width:200px;" value ="LIST ALL SALES" onClick="document.location.href = 'sales/list.php'">

<br /><br /><br /><br />
<input type="button" id = "ar" class ="button" style="width:200px;" value ="AR LIST" onClick="submitAction('clients/list.php')">

<br /><br /><br /><br />
<input type="button" id = "cement" class ="button" style="width:200px;" value ="CEMENT LIST" onClick="submitAction('products/list.php')">

<br /><br /><br /><br />
<input type="button" id = "reports" class ="button" style="width:200px;" value ="REPORTS" onClick="submitAction('reportpage.php')">

</div>
    </form>

</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>