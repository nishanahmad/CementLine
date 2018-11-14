<?php
session_start();
if(isset($_SESSION['user_name']))
{
	require '../connect.php';
    
	$clients = mysqli_query($con,"SELECT * FROM clients ORDER BY name") or die(mysqli_error($con));
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
					<h2 style="margin-left:45%;"><i class="fa fa-user"></i> Clients List</i></h2><br/>
					<form id="searchbox" class="form-inline col-md-offset-3">
						<input type="text" data-column="2"  style="margin-left:10px;" class="form-control" placeholder="Name">
						<input type="text" data-column="3"  style="margin-left:10px;" class="form-control" placeholder="Phone">	
						<input type="text" data-column="4"  style="margin-left:10px;" class="form-control" placeholder="Shop">					
						<input type="text" data-column="5"  style="margin-left:10px;width:100px;" class="form-control" placeholder="Status">					
					</form>	
					<section style="margin:40px;margin-left:20%;">
						<table class="table table-bordered table-striped" style="width:65%" id="clients">
							<thead class="cf">
								<tr>
									<th style="width:7%;"/>
									<th style="width:5%;">Id</th>
									<th>Name</th>
									<th style="min-width:120px;">Phone</th>
									<th style="min-width:150px;">Shop</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach($clients as $client)
							{																												?>
								<tr>
								<td align="center"><a href="view.php?id=<?php echo $client['id'];?>" class="btn btn-theme" style="padding: 0px 15px;font-size: 13px;">View</a></td>
								<td><?php echo $client['id'];?></td>
								<td><?php echo $client['name'];?></td>
								<td><?php echo $client['mobile'];?></td>
								<td><?php echo $client['shop'];?></td>																<?php
								if($client['isActive'])
								{																									?>	
									<td>Active</td>																					<?php									
								}																									
								else
								{																									?>
									<td>Suspended</td>																				<?php
								}																									?>
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