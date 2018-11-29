<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$products = mysqli_query($con,"SELECT id,name FROM products WHERE isActive = 1 ORDER BY name ASC");																?>
	<html lang="en" class="no-js">
		<head>
			<link rel="stylesheet" href="../css/qTable.css">	
		</head>
		<body>
			<div align="center">
				<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
				<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
			</div>	
			<br/><br/>
			<form method="post" action="insert.php" autocomplete="off">
				<table class="qTable" id="tbl" align="center" >
					<tr>
						<th style="width:15%;text-align:center;">Product</th>
						<th style="text-align:center;">Rate</th>
						<th style="text-align:center;">Special Discount</th>
					</tr>																																<?php 			
					$count = 1;
					while($count<10)
					{																																	?>
						<tr>
							<td style="text-align:center;">
								<select name="product<?php echo $count;?>" class="txtField">
									<option value = "">---Select---</option>																			<?php
									foreach($products as $product) 
									{																													?>
										<option value="<?php echo $product['id'];?>"><?php echo $product['name'];?></option>										<?php								
									}																													?>
								</select>					
							</td>	
							<td style="text-align:center;"><input type="text" name="rate<?php echo $count;?>"/></td>
							<td style="text-align:center;"><input type="text" name="sd<?php echo $count;?>"/></td>
						</tr>																															<?php				
						$count++;
					}																																	?>			
					<tr>
						<td colspan="3"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
					</tr>					
				</table>
				
			</form>	
		</body>	
	</html>																																			<?php
}
else
	header("Location:../index.php");																												?>	