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
<br />
<!-- <div align="center"><img src="Images/logo.png"></div>       -->
    <script type="text/javascript">

    function submitAction(act) {
         document.sample.action = act;
         document.sample.submit();

    }
    </script>
	
    <form name ="sample" action="default.php">

<br /><br /><br /><br />
<div align="center">

<input  type="button" id = "addnewsale" class ="button" style="width:200px;"  value ="ADD NEW SALE" onClick="submitAction('entryPage.php')">

<br /><br /><br /><br />
<input type="button" id = "list_today_sales" class ="button" style="width:200px;" value ="LIST TODAY'S SALES" onClick="document.location.href = 'list_today_sales.php?ar=all'">

<br /><br /><br /><br />
<input type="button" id = "list_all_sales" class ="button" style="width:200px;" value ="LIST ALL SALES" onClick="document.location.href = 'list_all_sales.php?ar=all'">

<br /><br /><br /><br />
<input type="button" id = "ar" class ="button" style="width:200px;" value ="AR LIST" onClick="submitAction('arList.php')">

<br /><br /><br /><br />
<input type="button" id = "cement" class ="button" style="width:200px;" value ="CEMENT LIST" onClick="submitAction('cementList.php')">

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