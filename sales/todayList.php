<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

	require '../connect.php';

	$id = $_GET['client'];
	if($id != 'all')
	{
		$result = mysqli_query($con,"SELECT * FROM sales  WHERE client=$id AND date >= CURDATE() order by bill_no asc ") or die(mysqli_error($con));				 	 	
	}
	else
		$result = mysqli_query($con,"SELECT * FROM sales WHERE date >= CURDATE() order by bill_no asc  ") or die(mysqli_error($con));				 	 
								
	$clients = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name ASC");	
	foreach($clients as $client)
	{
		$clientMap[$client['id']] = $client['name'];
	}	
	
	$products = mysqli_query($con,"SELECT id,name FROM products ORDER BY name ASC");	
	foreach($products as $product)
	{
		$productNameMap[$product['id']] = $product['name'];
	}		
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">
<title>Sales List</title>
<link rel="stylesheet" type="text/css" href="../css/styles.css" />
</head>
<body>
<form name="frmsales" method="post" action="" >
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
</div>
<br>
<div align="center">
<select name="client" id="client" onchange="document.location.href = 'todayList.php?client=' + this.value" class="txtField">
    <option value = "">--SELECT--</option>
	<option value = "all">ALL</option>																						    <?php
	foreach($clients as $client)
	{																															?>
		<option value="<?php echo $client['id'];?>"><?php echo $client['name'];?></option>																			<?php	
	}

     
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
<td>PRODUCT</td>
<td>QTY</td>
<td>RATE</td>
<td>BILL NO</td>
<td>CUST. NAME</td>
<td>CUST. PHONE</td>
<td>REMARKS</td>
</tr>
	<?php
	
	$i=0;

        $productMap = array();
        $qty=0;
        $total = 0;
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
        {   
            $productId = $row['product'];
            if (array_key_exists($productId,$productMap))
            {   
                $productMap[$productId] = $productMap[$productId] + $row["qty"];
                $total = $total + $row["qty"];
            }	
            else
            {
                $productMap[$productId] = $row["qty"];
                $total = $total + $row["qty"];
            }																																?>
	
<tr class="blue">
	<td><a href="edit.php?id=<?php echo $row['id']; ?>" class="link"><img alt='Edit' title='Edit' src='../images/edit.png' width='20px' height='20px' hspace='10' /></a></td>  
	<td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
	<td><?php echo $clientMap[$row['client']]; ?></td>
	<td><?php echo $row['truck_no']; ?></td>
	<td><?php echo $productNameMap[$row['product']]; ?></td>
	<td><?php echo $row['qty']; ?></td>
	<td><?php echo $row['rate'] - $row['cd'] - $row['qd'] - $row['sd']; ?></td>
	<td><?php echo $row['bill_no']; ?></td>
	<td><?php echo $row['customer_name']; ?></td>
	<td><?php echo $row['customer_phone']; ?></td>
	<td><?php echo $row['remarks']; ?></td>
</tr>

	<?php
	$i++;
	}
        foreach($productMap as $product=>$qty)
        {?>
            <div align="center" style="font:20px bold ;color:#000000"><?php echo $productNameMap[$product].  " = " .$qty;?></div><?php
        }?>    
        <br>
		<div align="center" style="font:20px bold;color:#000000">TOTAL = <?php echo $total;?></div>
</table>
</form>
<br><br><br>
	<script src="bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
</body></html>

<?php
}
else
	header("Location:../index.php");
?>