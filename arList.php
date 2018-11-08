<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	
	
require 'connect.php';

		$result = mysqli_query($con,"SELECT ar_id,ar_name FROM ar_details order by ar_name asc  ");
			
		if ( false===$result ) 
		{
			printf("error: %s\n", mysqli_error($con));
		}

?>

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
<title>AR List</title>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
    
<div style="width:40%;margin: 0 auto;">
<div align="center" style="padding-bottom:5px;">
<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="arInsertPage.php" class="link"><img alt='Add' title='Add New' src='images/addNew.png' width='60px' height='60px'/></a>
</div>
<br>

<table id="datatables" class="display" width="100%">
 <thead>
<tr>
<th>EDIT</th>
<th>AR NAME</th>
<th>DELETE</th>
</tr>
</thead>
 <tbody>
<?php
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))  
{
?>
<tr>
<td><a href="arEditPage.php?ar_id=<?php echo $row["ar_id"]; ?>" class="link"><img alt='Edit' title='Edit' src='images/edit.png' width='20px' height='20px' hspace='10' /></a></td>
<td><?php echo $row['ar_name']?></td>
<td><a href="arDelete.php?ar_id=<?php echo $row["ar_id"]; ?>"  class="link" onclick="return confirm('Are you sure you want to permanently delete this AR ?')">
		<img alt='Delete' title='Delete' src='images/delete.png' width='25px' height='25px'hspace='10' /></a></td>

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