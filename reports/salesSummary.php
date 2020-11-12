<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../navbar.php';
  	
	if(isset($_GET['from']))
		$fromDate = date("Y-m-d", strtotime($_GET['from']));		
	else
		$fromDate = date("Y-m-d");		
	
	if(isset($_GET['brand']))
		$urlBrand = date("Y-m-d", strtotime($_GET['from']));		
	else
		$fromDate = date("Y-m-d");			

	if(isset($_GET['to']))		
		$toDate = date("Y-m-d", strtotime($_GET['to']));		
	else
		$toDate = date("Y-m-d");		
	
	if(isset($_GET['brand']))
	{
		$brand = (float)$_GET['brand'];
		if($brand != 'all')
		{
			$productIdsMap = null;
			$brandProducts = mysqli_query($con,"SELECT id FROM products WHERE brand = $brand") or die(mysqli_error($con));		 
			foreach($brandProducts as $bp)
				$productIdsMap[$bp['id']] = null;
			
			$productIds = implode("','",array_keys($productIdsMap));							
		}
	}
	else
		$brand = 'all';
	
	
	if($brand == 'all')
		$salesList = mysqli_query($con, "SELECT client,product,SUM(qty) FROM sales WHERE date >= '$fromDate' AND date <= '$toDate' GROUP BY client,product" ) or die(mysqli_error($con));
	else
		$salesList = mysqli_query($con, "SELECT client,product,SUM(qty) FROM sales WHERE date >= '$fromDate' AND date <= '$toDate' AND product IN ('$productIds') GROUP BY client,product" ) or die(mysqli_error($con));
		

	$products = mysqli_query($con, "SELECT * FROM products" ) or die(mysqli_error($con));	
	foreach($products as $pro)
	{
		$productMap[$pro['id']] = $pro['name'];
	}
	
	$brandsQuery = mysqli_query($con, "SELECT * FROM brands ORDER BY name" ) or die(mysqli_error($con));	
	foreach($brandsQuery as $brnd)
		$brands[$brnd['id']] = $brnd['name'];
	
	
	$arObjects = mysqli_query($con, "SELECT * FROM clients order by name ASC" ) or die(mysqli_error($con));	
	foreach($arObjects as $ar)
	{
		$arNameMap[$ar['id']] = $ar['name'];
		$arShopMap[$ar['id']] = $ar['shop'];
		$arPhoneMap[$ar['id']] = $ar['mobile'];
	}
	
	$tallyFlag = false;
	if($fromDate == $toDate && $brand == 'All')
	{
		$tallyFlag = true;
		$tallyObjects = mysqli_query($con, "SELECT * FROM tally_day_check WHERE date = '$toDate'" ) or die(mysqli_error($con));
		foreach($tallyObjects as $tally)
			$tallyMap[$tally['ar']] = $tally['checked_by'];
	}
	
	$userObjects = mysqli_query($con, "SELECT * FROM users" ) or die(mysqli_error($con));
	foreach($userObjects as $user)
		$userMap[$user['user_id']] = $user['user_name'];	
		
	if($_POST)
	{
		$URL='salesSummary.php?from='.$_POST['fromDate'].'&to='.$_POST['toDate'].'&brand='.$_POST['brand'];
		echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';				
	}	
?>
	<html>
		<head>
			<title>Sales Summary AR</title>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link href="../css/styles.css" rel="stylesheet" type="text/css">
			<link href="../css/navbarMobile.css" media="screen and (max-device-width: 768px)" rel="stylesheet" type="text/css">
			<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
			<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
			<script>
			$(document).ready(function() {
				var pickerOpts = { dateFormat:"dd-mm-yy"}; 
				$( "#fromDate" ).datepicker(pickerOpts);
				
				var pickerOpts2 = { dateFormat:"dd-mm-yy"}; 
				$( "#toDate" ).datepicker(pickerOpts2);		

				$(".maintable").tablesorter(); 
				var $table = $('.maintable');
			});
			</script>	
			<style> 
				.green{
					font-weight:bold;
					font-style:italic;
					color:LimeGreen			
				}
			</style> 
			
		</head>
		<body>
			<nav class="navbar navbar-light bg-light sticky-top bottom-nav justify-content-center">
				<span class="navbar-brand" style="font-size:25px;"><i class="fa fa-line-chart"></i> Summary Report</span>
			</nav>
			<div style="width:100%;" class="mainbody">	
				<br/><br/>
				<div align="center">
					<form method="post" action="" autocomplete="off">
						<div class="row" style="margin-left:30%">
							<div style="width:220px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;From</span>
									<input type="text" required name="fromDate" id="fromDate" class="form-control" autocomplete="off" value="<?php echo date('d-m-Y',strtotime($fromDate)); ?>">
								</div>
							</div>
							<div style="width:220px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="far fa-calendar-alt"></i>&nbsp;To</span>
									<input type="text" required name="toDate" id="toDate" class="form-control" value="<?php echo date('d-m-Y',strtotime($toDate)); ?>">
								</div>
							</div>
							<div style="width:300px;">
								<div class="input-group">
									<span class="input-group-text col-md-5"><i class="fa fa-shield"></i>&nbsp;Brand</span>
									<select name="brand" id="brand" required class="form-control">
										<option value="all">ALL</option>																															<?php
										foreach($brands as $id => $name) 
										{																																			?>
											<option <?php if($brand == $id) echo 'selected';?> value="<?php echo $id;?>"><?php echo $name;?></option>							<?php
										}																																							?>
									</select>					
								</div>
							</div>
						</div>
						<br/>
						<div class="justify-content-center">
							<input type="submit" class="btn" style="background-color:#54698D;color:white;" value="Search">		
						</div>			
					</form>																													
					<br/>
					<div id="content-desktop">																																						<?php 
						if($tallyFlag)
						{																																											?>
							<a href="tallyVerification.php?date=<?php echo $toDate;?>" class="btn" style="background-color:#E6717C;color:white;float:right;margin-right:30px;">Verify Individual Sale</a><?php
						}																																												  ?>
					</div>
					<br/>
					<div class="col-md-8 table-responsive-sm">
					<table class="maintable table table-hover table-bordered table-responsive">
						<thead>
							<tr class="table-success">
								<th style="text-align:left;" class="header" scope="col"><i class="fa fa-map-o"></i> Client</th>
								<th style="width:20%;" class="header" scope="col"><i class="fa fa-shield"></i> Product</th>
								<th style="width:12%;text-align:center" class="header" scope="col"><i class="fab fa-buffer"></i> Qty</th>
								<th style="text-align:left;" class="header" scope="col"><i class="fas fa-store"></i> Shop Name</th>	
								<th style="width:15%;" class="header" scope="col"><i class="fa fa-mobile"></i> Phone</th>	<?php
								if($tallyFlag)
								{																								?>
									<th id="content-desktop" class="header" scope="col">VerifiedBy</th>												<?php
								}																								?>
							</tr>
						</thead>																								
						<tbody class="tablesorter-no-sort">																		<?php
						$total = 0;
						foreach($salesList as $arSale)
						{																										?>
							<tr id="<?php echo $arNameMap[$arSale['client']];?>">
								<td style="text-align:left;"><?php echo $arNameMap[$arSale['client']];?></td>
								<td style="text-align:left;"><?php echo $productMap[$arSale['product']];?></td>
								<td style="text-align:center"><b><?php echo $arSale['SUM(qty)'];?></b></td>
								<td style="text-align:left;"><?php echo $arShopMap[$arSale['client']];?></td>			
								<td><?php echo $arPhoneMap[$arSale['client']];?></td>										<?php
								if($tallyFlag == true)
								{		
									if(isset($tallyMap[$arSale['client']]))
									{		
										$userId = $tallyMap[$arSale['client']];																									?>
										<td id="content-desktop"><font style="font-weight:bold;font-style:italic;"><?php echo $userMap[$userId];?></font></td>										<?php
									}
									else
									{																																			?>
										<td id="content-desktop"><button class="btn" value="<?php echo $arSale['client'];?>" style="background-color:#E6717C;color:white;" onclick="callAjax(this.value)">Verify</button></td>																											<?php			
									}
								}																																				?>																																
							</tr>																																				<?php	
							$total = $total + $arSale['SUM(qty)'];
						}																																						?>	
							<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
								<td colspan="2" style="text-align:right" >TOTAL</td>
								<td colspan="2"><?php echo $total;?></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
				<br/><br/><br/>
			</div>
			<script>
				function callAjax(ar){
					const queryString = window.location.search;
					const urlParams = new URLSearchParams(queryString);
					var date = urlParams.get('to');
					if(!date)
					{
						date = new Date();
						var dd = String(date.getDate()).padStart(2, '0');
						var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
						var yyyy = date.getFullYear();

						date = dd + '-' + mm + '-' + yyyy;
					}
					$.ajax({
						type: "POST",
						url: "ajax/updateDayTally.php",
						data:'ar='+ar +'&date='+date,
						success: function(response){
							if(response != false){
								$('#'+response).find('td').eq(5).text('VERIFIED!');
								$('#'+response).find('td').eq(5).addClass("green")
							}
							else{
								alert('Some error occured. Try again');
								location.reload();
							}
						}
					});	  
				 }
			</script>
		</body>	
	</html>																																											<?php
}
else
	header("Location:../index.php");	
?>