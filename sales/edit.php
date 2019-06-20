<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$products = mysqli_query($con,"SELECT id,name FROM products WHERE isActive = 1 ORDER BY id ASC") or die(mysqli_error($con));	
	$clients = mysqli_query($con,"SELECT id,name FROM clients ORDER BY name") or die(mysqli_error($con));	
	foreach($clients as $client)
	{
		$clientMap[$client['id']] = $client['name']; 
	}
	$result = mysqli_query($con,"SELECT * FROM sales WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));	
	$row= mysqli_fetch_array($result,MYSQLI_ASSOC);
	?>

	<html>
	<head>
		<title>Edit Sale <?php echo $row['id']; ?></title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
		<link rel="stylesheet" href="../css/button.css">
		<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
			<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
		<script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
		<script>
		$(function() {
			$("#client").select2();
			$("#product").select2();
			
			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
			$( "#datepicker" ).datepicker(pickerOpts);	
				
			$.ajax({
				type: "POST",
				url: "getRate.php",
				data:'date='+$("#datepicker").val()+'&product='+$("#product").val(),
				success: function(data){
					var rate = data.split("-")[0];
					var wd = data.split("-")[1];
					$("#rate").val(rate);
					$("#wd").val(wd);
					refreshRate();
				}
			});	
			$.ajax({
				type: "POST",
				url: "getCD.php",
				data:'date='+$("#datepicker").val()+'&product='+$("#product").val()+'&client='+$("#client").val(),
				success: function(data){
					$("#cd").val(data);
					refreshRate();
				}
			});		
			$.ajax({
				type: "POST",
				url: "getSD.php",
				data:'date='+$("#datepicker").val()+'&product='+$("#product").val()+'&client='+$("#client").val(),
				success: function(data){
					$("#sd").val(data);
					refreshRate();
				}
			});	

			$("#datepicker").change(function(){
				var product = $("#product").val();
				var client = $("#client").val();
				$.ajax({
					type: "POST",
					url: "getRate.php",
					data:'date='+$(this).val()+'&product='+product,
					success: function(data){
						var rate = data.split("-")[0];
						var wd = data.split("-")[1];
						$("#rate").val(rate);
						$("#wd").val(wd);
						refreshRate();
					}
				});
				$.ajax({
					type: "POST",
					url: "getCD.php",
					data:'date='+$(this).val()+'&product='+product+'&client='+client,
					success: function(data){
						$("#cd").val(data);
						refreshRate();
					}
				});		
				$.ajax({
					type: "POST",
					url: "getSD.php",
					data:'date='+$(this).val()+'&product='+product+'&client='+client,
					success: function(data){
						$("#sd").val(data);
						refreshRate();
					}
				});		
			});
			
			
			
			$("#product").change(function(){
				var date = $("#datepicker").val();
				var client = $("#client").val();
				$.ajax({
					type: "POST",
					url: "getRate.php",
					data:'product='+$(this).val()+'&date='+date,
					success: function(data){
						var rate = data.split("-")[0];
						var wd = data.split("-")[1];
						$("#rate").val(rate);
						$("#wd").val(wd);
						refreshRate();
					}
				});
				$.ajax({
					type: "POST",
					url: "getCD.php",
					data:'product='+$(this).val()+'&date='+date+'&client='+client,
					success: function(data){
						$("#cd").val(data);
						refreshRate();
					}
				});				
				$.ajax({
					type: "POST",
					url: "getSD.php",
					data:'product='+$(this).val()+'&date='+date+'&client='+client,
					success: function(data){
						$("#sd").val(data);
						refreshRate();
					}
				});						
			});
			
			$("#client").change(function(){
				var date = $("#datepicker").val();
				var product = $("#product").val();
				$.ajax({
					type: "POST",
					url: "getCD.php",
					data:'client='+$(this).val()+'&date='+date+'&product='+product,
					success: function(data){
						$("#cd").val(data);
						refreshRate();
					}
				});			
				$.ajax({
					type: "POST",
					url: "getSD.php",
					data:'client='+$(this).val()+'&date='+date+'&product='+product,
					success: function(data){
						$("#sd").val(data);
						refreshRate();
					}
				});					
			});

			$("#bd").change(function(){
				refreshRate();
			});						
		});

		function refreshRate()
		{
			var rate=document.getElementById("rate").value;
			var cd=document.getElementById("cd").value;
			var sd=document.getElementById("sd").value;
			var wd=document.getElementById("wd").value;
			var bd=document.getElementById("bd").value;
			
			$('#final').val(rate-cd-sd-wd-bd);
		}	
		</script>
	</head>
	<body>
		<form name="frmUser" method="post" action="update.php">
			<input hidden name="id" value="<?php echo $row['id'];?>">
			<div style="width:100%;">
				<div align="center" style="padding-bottom:5px;">
					<a href="../index.php" class="link"><img alt='Home' title='Home' src='../images/home.png' width='50px' height='50px'/></a>&nbsp;&nbsp;
					<a href="todayList.php?client=all" class="link"><img alt='List' title='List' src='../images/list_icon.jpg' width='50px' height='50px'/></a>
					<a href="modified_by.php?id=<?php echo $row["id"]; ?>"  class="link" >
						<img align="right" alt= 'Modified By' title='Modified By' src='../images/user.png' width='40px' height='50px'hspace='10'  />
					</a>
				</div>
				<br>
				<div align ="center">
					<table border="0" cellpadding="10" cellspacing="0" width="80%" align="center" class="tblSaveForm">
						<tr class="tableheader">
							<td colspan="4" style="text-align:center;"><b><font size="4">Edit Sale <?php echo $row['id']; ?> </font><b></td>
						</tr>
						<tr>
							<td><label>Date</label></td>
							<td><input type="text" id="datepicker" name="date" class="txtField" 
								value="<?php 
										$originalDate1 = $row['date'];
										$newDate1 = date("d-m-Y", strtotime($originalDate1));
										echo $newDate1; ?>">
							</td>

							<td><label>Bill No </label></td>
							<td><input type="text" name="bill" class="txtField" value="<?php echo $row['bill_no']; ?>"></td>
						</tr>
						<tr>
							<td><label>Client</label></td>
							<td><select name="client" id="client" required class="txtField">
								<option value = "<?php echo $row['client'];?>"><?php echo $clientMap[$row['client']];?></option>
								<?php
									foreach($clientMap as $clientId => $clientName)
									{?>
										<option value="<?php echo $clientId;?>"><?php echo $clientName;?></option>			
							<?php	}
							?>
								  </select>
							</td>

							<td><label>Truck No </label></td>
							<td><input type="text" name="truck" class="txtField" value="<?php echo $row['truck_no']; ?>"></td>
						</tr>
						<tr>
							<td><label>Product</label></td>
							<td><select name="product" id="product" required class="txtField">									<?php
									foreach($products as $product) 
									{																							?>
										<option <?php if($row['product'] == $product['id']) echo 'selected';?> value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>		<?php	
									}																							?>
								</select>
							</td>
							<td><label>Customer Phone</label></td>
							<td><input type="text" name="customerPhone" class="txtField" value="<?php echo $row['customer_phone']; ?>"></td>
						</tr>	
						<tr>
							<td><label>Qty</label></td>
							<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" value="<?php echo $row['qty'];?>" title="Input a valid number"></td>							
							<td><label>Customer Name</label></td>
							<td><input type="text" name="customerName" class="txtField" value="<?php echo $row['customer_name']; ?>"></td>
						</tr>
						<tr>
							<td><label>Bill Discount</label></td>
							<td><input type="text" name="bd" id="bd" class="txtField" pattern="[0-9]+" title="Input a valid number" value="<?php echo $row['discount'];?>"></td>			
							<td><label>Address Part 1</label></td>
							<td><input type="text" name="address1" class="txtField" value="<?php echo $row['address1']; ?>"></td>
						</tr>
						<tr>
							<td><label>Cash Discount</label></td>
							<td><input type="text" readonly name="cd" class="txtField" id="cd"></td>	
							<td><label>Address Part 2</label></td>
							<td><input type="text" name="address2" class="txtField" value="<?php echo $row['address2']; ?>"></td>
						</tr>
						<tr>
							<td><label>Special Discount</label></td>
							<td><input type="text" readonly name="sd" class="txtField" id="sd"></td>							
							<td><label>Remarks</label></td>
							<td><input type="text" name="remarks" class="txtField" value="<?php echo $row['remarks']; ?>"></td>
						</tr>
						<tr>
							<td><label>Wagon Discount</label></td>
							<td><input type="text" readonly name="wd" class="txtField" id="wd"></td>							
							<td></td>
							<td></td>
						</tr>						
						<tr>
							<td><label>Rate</label></td>
							<td><input type="text" readonly name="rate" class="txtField" id="rate"></td>							
							<td></td>
							<td></td>
						</tr>												
						<tr>
							<td><label>Final Rate</label></td>
							<td><input readonly id="final" class="txtField"></td>			
							<td></td>
							<td></td>	
						</tr>
						<tr>
							<td colspan="4" align = "center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
						</tr>
					</table>
					<br/><br/>
				</div>
				<a href="delete.php?id=<?php echo $row['id'];?>" style="float:right;width:50px;margin-right:150px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
			</div>
			<br/><br/><br/><br/>		
		</form>
		<br/><br/><br/><br/>		
	</body>
	</html>																								<?php
}
else
	header("Location:../index.php");
