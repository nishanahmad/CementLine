<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
    
	$id = $_GET['id'];
	$sql = mysqli_query($con,"SELECT * FROM products WHERE id='$id'") or die(mysqli_error($con));
	$product = mysqli_fetch_array($sql,MYSQLI_ASSOC);	
	$brands = mysqli_query($con,"SELECT * FROM brands ORDER BY name") or die(mysqli_error($con));
?>
<html>
	<head>
		<title><?php echo $product['name'];?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/dashio.css" rel="stylesheet">
		<link href="../css/dashio-responsive.css" rel="stylesheet">	
		<link href="../css/font-awesome.min.css" rel="stylesheet">		
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	</head>
	<section class="wrapper">
		<h2><i class="fa fa-cube" style="margin-right:.5em;margin-left:.5em;"></i><?php echo $product['name'];?></h3>
		<div class="row mt">
			<div class="col-lg-8">
				<div class="form-panel">
					<h4 class="mb"><i class="fa fa-angle-right" style="margin-right:.5em;"></i>Edit</h4>
					<form class="form-horizontal style-form"  action="update.php" method="post">
						<input type="hidden" name="id" value="<?php echo $product['id'];?>">
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Name</label>
							<div class="col-sm-6">
								<input type="text" name="name" value="<?php echo $product['name'];?>" class="form-control">
							</div>
						</div>					
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Brand</label>
							<div class="col-sm-6">
								<select required name="brand" class="form-control">
									<option value = "">---Select---</option>																			<?php
									foreach($brands as $brand) 
									{																													
										if($product['brand'] == $brand['id'])
										{																												?>
											<option selected value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>										<?php								
										}
										else
										{																												?>
											<option value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>										<?php		
										}
									}																													?>
								</select>
							</div>
						</div>					
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Status</label>
							<div class="col-sm-6">
								<select required name="status" class="form-control">
									<option <?php if($product['isActive']) echo 'selected';?> value = "1">Active</option>
									<option <?php if(!$product['isActive']) echo 'selected';?> value = "0">Suspended</option>
								</select>
							</div>
						</div>																					
						<button type="submit" class="btn btn-primary" style="margin-left:200px;" tabindex="4">Update</button> 
						<a href="list.php" class="btn btn-default" style="margin-left:10px;">Cancel</a>
						<br/><br/>
					</form>
				</div>
			</div>
		</div>
	</section>
</html>	
<?php
}
else
	header("Location:../index.php");
?>