<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

	require 'connect.php';

	if($_GET['ar'] != 'all')
	{
		$result = mysqli_query($con,"SELECT sales_id, entry_date,ar,truck_no,cement,qty,remarks, bill_no, customer_name, customer_phone, address1, address2
								FROM sales_entry  WHERE ar='" . $_GET['ar'] . "' AND entry_date >= CURDATE() order by bill_no asc ");
			
		if ( false===$result ) 
		{
			printf("error: %s\n", mysqli_error($con));
		}
	
	}
	else
		$result = mysqli_query($con,"SELECT sales_id, entry_date,ar,truck_no,cement,qty,remarks, bill_no, customer_name, customer_phone, address1, address2
								FROM sales_entry WHERE entry_date >= CURDATE() order by bill_no asc  ");
			
		if ( false===$result ) 
		{
			printf("error: %s\n", mysqli_error($con));
		}

?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<title>Sales List</title>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
<form name="frmsales" method="post" action="" >
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="entryPage.php" class="link"><img alt='Add' title='Add New' src='images/addnew.png' width='60px' height='60px'/></a>
</div>
<br>
<div align="center">
<select name="ar" id="ar" onchange="document.location.href = 'list_today_sales.php?ar=' + this.value" class="txtField">
    <option value = "">--SELECT--</option>
	<option value = "all">ALL</option>
    <?php
	$con = mysqli_connect("localhost","nishan","123","cementline");
    $queryusers = "SELECT `ar_name` FROM `ar_details` ";
    $db = mysqli_query($con,$queryusers);
    while ( $d=mysqli_fetch_assoc($db)) {
     echo "<option value='".$d['ar_name']."'>".$d['ar_name']."</option>";    }
    ?>
      </select>
</div>	  

<br>
<table width="100%" class="table-responsive">
<tr class="tableheader">
<td>EDIT</td>
<td>Date</td>
<td>AR</td>
<td>TRUCK NO</td>
<td>CEMENT</td>
<td>QTY</td>
<td>BILL NO</td>
<td>CUST. NAME</td>
<td>CUST. PHONE</td>
<td>REMARKS</td>
<td>DELETE</td>
</tr>
	<?php
	
	$i=0;

        $cement_array = array();
        $qty=0;
        $total = 0;
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
        {
            if($i%2==0)
            $classname="evenRow";
            else
            $classname="oddRow";
            
            $explode = explode(' ', $row["cement"]);
            $cement = strtolower($explode[0]);
            if (array_key_exists($cement,$cement_array))
            {   
                $cement_array[$cement] = $cement_array[$cement] + $row["qty"];
                $total = $total + $row["qty"];
            }	
            else
            {
                $cement_array[$cement] = $row["qty"];
                $total = $total + $row["qty"];
            }

            
	?>
	
<tr class="blue">
	<?php
	$originalDate = $row["entry_date"];
	$newDate = date("d-m-Y", strtotime($originalDate));
	?>

<td><a href="editSales.php?sales_id=<?php echo $row["sales_id"]; ?>" class="link"><img alt='Edit' title='Edit' src='images/edit.png' width='20px' height='20px' hspace='10' /></a></td>  
<td><?php echo $newDate; ?></td>
<td><?php echo $row["ar"]; ?></td>
<td><?php echo $row["truck_no"]; ?></td>
<td><?php echo $row["cement"]; ?></td>
<td><?php echo $row["qty"]; ?></td>
<td><?php echo $row["bill_no"]; ?></td>
<td><?php echo $row["customer_name"]; ?></td>
<td><?php echo $row["customer_phone"]; ?></td>
<td><?php echo $row["remarks"]; ?></td>
<td>
<a href="delete_sales.php?sales_id=<?php echo $row["sales_id"]; ?>"  class="link" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">
		<img alt='Delete' title='Delete' src='images/delete.png' width='25px' height='25px'hspace='10' /></a>
</td>
</tr>

	<?php
	$i++;
	}
        foreach($cement_array as $cement=>$qty)
        {
            echo "<div align ='center' style ='font:20px bold ;color:#000000'> $cement = $qty </div>";
        }    
        echo "<br>";
	echo "<div align ='center' style ='font:20px bold;color:#000000'> TOTAL = $total </div>";
	
	
	

	
	?>
</table>
</form>
<br><br><br>
	<script src="bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
</body></html>

<?php
}

else
	header("Location:loginPage.php");

?>