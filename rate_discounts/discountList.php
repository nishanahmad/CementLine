<?php
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';
    
	$discounts = mysqli_query($con,"SELECT * FROM discounts ORDER BY date DESC") or die(mysqli_error($con));
	
	$clients = mysqli_query($con,"SELECT * FROM clients") or die(mysqli_error($con));
	foreach($clients as $client)
		$clientMap[$client['id']] = $client['name'];
		
	$products = mysqli_query($con,"SELECT * FROM products") or die(mysqli_error($con));
	foreach($products as $product)
		$productMap[$product['id']] = $product['name'];		
		
																														?>
<html>
	<head>
		<title>Clients</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/dashio.css" rel="stylesheet">
		<link href="../css/dashio-responsive.css" rel="stylesheet">	
		<link href="../css/font-awesome.min.css" rel="stylesheet">		
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/moment.min.js"></script>
		<style>
		.dataTables_length{
		  display:none;
		}
		.dataTables_paginate{
		  display:none;
		}
		</style>
	</head>
	<body>
		<div class="row content-panel">
			<div class="col-md-12" align="center">
				<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
			</div>
		</div>
		<div class="row mt">
			<div class="col-lg-12">
				<div class="content-panel">
					<h2 style="margin-left:45%;" ><i class="fa fa-gift"></i> Discounts</i></h2><br/>
					<form id="searchbox" class="form-inline col-md-offset-2">
						<input type="text" data-column="0"  style="margin-left:10px;" class="form-control" placeholder="Date">
						<input type="text" data-column="1"  style="margin-left:10px;" class="form-control" placeholder="Product">	
						<input type="text" data-column="2"  style="margin-left:10px;" class="form-control" placeholder="Client">					
						<input type="text" data-column="3"  style="margin-left:10px;width:100px;" class="form-control" placeholder="Type">					
					</form>	
					<section style="margin-top:40px;">
						<table class="table table-bordered table-striped col-md-offset-2" style="width:55%" id="discount">
							<thead class="cf">
								<tr>
									<th>Date</th>
									<th>Product</th>
									<th>Client</th>
									<th>Type</th>
									<th style="max-width:60px;">Amount</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach($discounts as $discount)
							{																												?>
								<tr>
									<td><?php echo date('d-m-Y',strtotime($discount['date']));?></td>
									<td><?php echo $productMap[$discount['product']];?></td>
									<td><?php if(isset($clientMap[$discount['client']])) echo $clientMap[$discount['client']];?></td>
									<td><?php if($discount['type'] == 'cd') echo 'Cash discount';
											  else if($discount['type'] == 'sd') echo 'Special discount';
											  else if($discount['type'] == 'wd') echo 'Wagon discount';?></td>
									<td><?php echo $discount['amount'];?></td>																									
								</tr>																								<?php
							}																										?>
							</tbody>
						</table>
					</section>
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function() {
			
			$.fn.dataTable.moment( 'DD-MM-YYYY' );			
			
			var table = $('#clients').DataTable({
				"iDisplayLength": 10000
			});
				
			$("#clients_filter").css("display","none");  // hiding global search box
			$('.form-control').on( 'keyup click', function () {   // for text boxes
				var i =$(this).attr('data-column');  // getting column index
				var v =$(this).val();  // getting search input value
				table.columns(i).search(v).draw();
			} );
			$('.select').on( 'change', function () {   // for select box
				var i =$(this).attr('data-column');  
				var v =$(this).val();  
				table.columns(i).search(v).draw();
			} );	

		} );
		</script>
	</body>
</html>
<?php
}
else
	header("Location:../index.php");
?>