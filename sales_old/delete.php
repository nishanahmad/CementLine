<?php
require '../connect.php';  

$id = $_GET["id"];
$sql= "DELETE FROM sales WHERE id=$id";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 	 		 
		 
		 
if($_GET['clicked_from'] == 'all_sales')
	header("Location:list.php?client=all");
else	
	header("Location:todayList.php?client=all");

?>