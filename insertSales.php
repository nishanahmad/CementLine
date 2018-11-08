<html>
<body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$originalDate = $_POST['date'];
	$newDate = date("Y-m-d", strtotime($originalDate));
	$ar = $_POST['ar'];
	$truck = $_POST['truck'];
	$cement = $_POST['cement'];
	$qty = $_POST['qty'];
	$remarks = $_POST['remarks'];
	$bill = $_POST['bill'];
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$entered_by = $_SESSION["user_name"];
	$entered_on = date('Y-m-d H:i:s');	


	$sql="INSERT INTO sales_entry (entry_date, ar, truck_no, cement, qty, remarks, bill_no, customer_name, customer_phone, address1, address2,entered_by,entered_on)
		 VALUES
		 ('$newDate', '$ar', '$truck', '$cement', '$qty', '$remarks', '$bill', '$customerName', '$customerPhone', '$address1', '$address2', '$entered_by', '$entered_on')";

	$result = mysqli_query($con, $sql);		 
		 
	if ( false===$result ) {
	printf("error: %s\n", mysqli_error($con));
	}

	else
	{
		header( "Location: entryPage.php" );
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