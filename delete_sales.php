<?php
require 'connect.php';  
 	$sql= "DELETE FROM sales_entry WHERE sales_id='" . $_GET["sales_id"] . "'";

	$result = mysqli_query($con, $sql);		 
		 
if ( false===$result ) {
  printf("error: %s\n", mysqli_error($con));
}

if($_GET['clicked_from'] == 'all_sales')
	header("Location:list_all_sales.php?ar=all");
else	
	header("Location:list_today_sales.php?ar=all");
?>