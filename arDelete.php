<?php
require 'connect.php';  
 	$sql= "DELETE FROM ar_details WHERE ar_id='" . $_GET["ar_id"] . "'";

	$result = mysqli_query($con, $sql);		 
		 
if ( false===$result ) {
  printf("error: %s\n", mysqli_error($con));
}

header("Location:arList.php");
?>