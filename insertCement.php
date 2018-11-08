<html>
<body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$cement = $_POST['cement'];

	$sql="INSERT INTO cement_details (cement_name) VALUES ('$cement')";

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