<?php
require 'connect.php';  
 	$sql= "DELETE FROM cement_details WHERE cement_id='" . $_GET["cement_id"] . "'";

	$result = mysqli_query($con, $sql);		 
		 
if ( false===$result ) {
  printf("error: %s\n", mysqli_error($con));
}

header("Location:cementList.php");
?>