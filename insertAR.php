<html>
<body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$ar = $_POST['ar'];

	$sql="INSERT INTO ar_details (ar_name) VALUES ('$ar')";

	$result = mysqli_query($con, $sql);		 
		 
	if ( false===$result ) {
	printf("error: %s\n", mysqli_error($con));
	}

	else
	{
		header( "Location: index.php" );
	}
	

	mysqli_close($con);
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
</body>
</html>