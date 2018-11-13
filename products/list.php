<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	
	
require '../connect.php';

$result = mysqli_query($con,"SELECT * FROM products ORDER BY name") or die(mysqli_error($con));				 	 

$brands = mysqli_query($con,"SELECT * FROM brands ORDER BY name") or die(mysqli_error($con));				 	 
foreach($brands as $brand)
{
	$brandMap[$brand['id']] = $brand['name'];
}																																					?>
<html>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/responsive/1.0.6/css/dataTables.responsive.css"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/responsive/1.0.6/js/dataTables.responsive.js"></script>
<script>
$(document).ready(function(){
$('#datatables').dataTable({
"scrollCollapse": true,
"paging":         false,
"responsive": true,
"bJQueryUI":true
});

})

</script>
<title>Products</title>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
<div style="width:40%;margin: 0 auto;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addNew.png' width='60px' height='60px'/></a>
</div>
<br>

<table id="datatables" class="display" width="100%">
 <thead>
<tr>
<th>Edit</th>
<th>Product Name</th>
<th>Brand</th>
</tr>
</thead>
 <tbody>
<?php
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))  
{
?>
<tr>
	<td><a href="edit.php?id=<?php echo $row["id"]; ?>" class="link"><img alt='Edit' title='Edit' src='../images/edit.png' width='20px' height='20px' hspace='10' /></a></td>
	<td><?php echo $row['name']?></td>
	<td><?php echo $brandMap[$row['brand']]?></td>
</tr>
<?php
}
?>
</tbody>
</table>
</html>


<?php
}
else
	header("Location:loginPage.php");
?>