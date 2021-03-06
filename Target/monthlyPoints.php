<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../functions/targetFormula.php';	

	$mainArray = array();
	if(isset($_GET['year']) && isset($_GET['month']))
	{
		$year = (int)$_GET['year'];
		$month = (int)$_GET['month'];		
	}	
	else
	{
		$year = (int)date("Y");
		$month = (int)date("m");
	}

	$zeroTargetMap = array();
	$zeroTargetList = mysqli_query($con,"SELECT * FROM target WHERE year = '$year' AND month  = '$month' AND target = 0") or die(mysqli_error($con));		 
	foreach($zeroTargetList as $zeroTarget)
	{
		$zeroTargetMap[$zeroTarget['client']] = null;
	}
	
	$zeroTargetIds = implode("','",array_keys($zeroTargetMap));	
	
	$arMap = array();
	$arObjects =  mysqli_query($con,"SELECT * FROM clients WHERE  isActive = 1 AND id NOT IN ('$zeroTargetIds') ORDER BY name ASC ") or die(mysqli_error($con));		 
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']]['name'] = $ar['name'];
		$arMap[$ar['id']]['mobile'] = $ar['mobile'];
		$arMap[$ar['id']]['shop'] = $ar['shop'];
	}				
	
	$arIds = implode("','",array_keys($arMap));
	$targetObjects = mysqli_query($con,"SELECT * FROM target WHERE  month = '$month' AND Year='$year' AND client IN('$arIds')") or die(mysqli_error($con));		 
	$targetMap = array();
	foreach($targetObjects as $target)
	{
		$targetMap[$target['client']]['target'] = $target['target'];
		$targetMap[$target['client']]['rate'] = $target['rate'];
		$targetMap[$target['client']]['payment_perc'] = $target['payment_perc'];
	}
	
	$miscProducts =  mysqli_query($con,"SELECT * FROM products WHERE brand = (SELECT id FROM brands WHERE name = 'MISC')") or die(mysqli_error($con));		 
	foreach($miscProducts as $miscProduct)
		$miscMap[$miscProduct['id']] = null;
	
	$miscIds = implode("','",array_keys($miscMap));
	
	$coromandelProducts =  mysqli_query($con,"SELECT * FROM products WHERE brand = (SELECT id FROM brands WHERE name = 'COROMANDEL')") or die(mysqli_error($con));		 
	foreach($coromandelProducts as $coromandelProduct)
		$coromandelMap[$coromandelProduct['id']] = null;
	
	$corIds = implode("','",array_keys($coromandelMap));	
	
	$otherSaleMap = array();
	$otherSales = mysqli_query($con,"SELECT client,SUM(qty) FROM sales WHERE '$year' = year(`date`) AND '$month' = month(`date`) AND client IN ('$arIds') AND product NOT IN('$corIds') AND product NOT IN('$miscIds') GROUP BY client") or die(mysqli_error($con));
	foreach($otherSales as $sale)
	{
		$otherSaleMap[$sale['client']] = $sale['SUM(qty)'];
	}
	
	$extraBagsMap = array();
	$extraBags = mysqli_query($con,"SELECT client,SUM(qty) FROM extra_bags WHERE '$year' = year(`date`) AND '$month' = month(`date`) AND client IN ('$arIds') AND product IN('$corIds') GROUP BY client") or die(mysqli_error($con));
	foreach($extraBags as $extraBag)
	{
		$extraBagsMap[$extraBag['client']] = $extraBag['SUM(qty)'];
	}	

	$sales = mysqli_query($con,"SELECT client,SUM(qty) FROM sales WHERE '$year' = year(`date`) AND '$month' = month(`date`) AND client IN ('$arIds') AND product IN('$corIds') GROUP BY client") or die(mysqli_error($con));		
	$mainArray = array();
	foreach($sales as $sale)
	{
		$arId = $sale['client'];
		$total = $sale['SUM(qty)'];
		if(isset($extraBagsMap[$arId]))
			$total = $total + $extraBagsMap[$arId];
			
		if(isset($targetMap[$arId]))
		{
			$points = round($total * $targetMap[$arId]['rate'],0);
			$actual_perc = round($total * 100 / $targetMap[$arId]['target'],0);
			$point_perc = getPointPercentage($actual_perc,$year,$month);			 
			$achieved_points = round($points * $point_perc/100,0);
			
			if($total > 0)
			{
				$payment_points = round($achieved_points * $targetMap[$arId]['payment_perc']/100,0);
				if(isset($extraBagsMap[$arId]))
					$payment_points = $payment_points - $extraBagsMap[$arId];
			}	
			else
				$payment_points = 0;			

			if(isset($extraBagsMap[$arId]))
				$total = $total - $extraBagsMap[$arId];
			$mainArray[$arId]['actual_sale'] = $total;
			$mainArray[$arId]['points'] = $points;
			$mainArray[$arId]['actual_perc'] = $actual_perc;
			$mainArray[$arId]['point_perc'] = $point_perc;
			$mainArray[$arId]['achieved_points'] = $achieved_points;
			$mainArray[$arId]['payment_points'] = $payment_points;			
		}
	}	
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/loader.css">	
<link rel="stylesheet" type="text/css" href="../css/responstable.css">
<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.floatThead.min.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script> 
<script src="../js/fileSaver.js"></script>
<script src="../js/tableExport.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$("#loader").hide();

	$("#Points").tablesorter(); 
	
 	$("#button").click(function(){
		$("table").tableExport({
				formats: ["xls"],    // (String[]), filetypes for the export
				bootstrap: false,
				ignoreCSS: ".ignore"   // (selector, selector[]), selector(s) to exclude from the exported file
		});
	});		

	var $table = $('.responstable');
	$table.floatThead();				
} );


function rerender()
{
	var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;

	var month=document.getElementById("jsMonth").value;

	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	$("#main").hide();
	$("#loader").show();
	window.location.href = hrf +"?year="+ year + "&month=" + month;
}
</script>

<title><?php echo getMonth($month); echo " "; echo $year; ?></title>
</head>
<body>
	<div id="loader" class="loader" align="center" style="background : #161616 url('../images/pattern_40.gif') top left repeat;height:100%">
		<br><br><br><br><br><br><br><br><br><br><br><br>
		<div class="circle"></div>
		<div class="circle1"></div>
		<br>
		<font style="color:white;font-weight:bold">Calculating ......</font>
	</div>
	<div align="center">
		<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a>
		<br><br>
		<select id="jsMonth" name="jsMonth" class="textarea" onchange="return rerender();">																				<?php	
			for($i=1;$i<=12;$i++) 
			{	
	?>			<option value="<?php echo $i;?>" <?php if($i == $month) echo 'selected';?>><?php echo getMonth($i);?></option>		<?php	
			}
	?>	</select>					
			&nbsp;&nbsp;

		<select id="jsYear" name="jsYear" class="textarea" onchange="return rerender();">																				<?php	
			$yearList = mysqli_query($con, "SELECT DISTINCT year FROM target ORDER BY year DESC") or die(mysqli_error($con));	
			foreach($yearList as $yearObj) 
			{
?>				<option value="<?php echo $yearObj['year'];?>" <?php if($yearObj['year'] == $year) echo 'selected';?>><?php echo $yearObj['year'];?></option>											<?php	
			}
?>		</select>
		<br><br>
		
		<img src="../images/excel.png" id="button" height="50px" width="45ypx" />
		<br/><br/>

		<table id="Points" class="responstable" style="width:90% !important">
		<thead>
			<tr>
				<th style="width:20%;text-align:left;">AR</th>
				<th style="width:12%;">Mobile</th>
				<th style="width:25%;text-align:left;">Shop</th>
				<th>Target</th>
				<th>Other Sale</th>
				<th>Cormandel Sale</th>
				<th>Rate</th>
				<th>Points</th>
				<th>Actual%</th>	
				<th>Point%</th>	
				<!--th>Payment%</th>	
				<th>Achieved Pnts</th-->
				<th>Points</th>	
			</tr>
		</thead>	
							
																																						<?php
			$totalTarget = 0;
			$totalOtherSale = 0;	
			$totalSale = 0;	
			$totalPoints = 0;		
			$totalPaymentPoints = 0;					
			foreach($targetMap as $arId => $targetArray)
			{		
				$target = $targetArray['target'];
				$rate = $targetArray['rate'];
				$payment_perc = $targetArray['payment_perc'];
				$totalTarget = $totalTarget + $target;
				if(!isset($mainArray[$arId]))
				{
					$mainArray[$arId]['actual_sale'] = null;
					$mainArray[$arId]['points'] = null;
					$mainArray[$arId]['actual_perc'] = null;
					$mainArray[$arId]['point_perc'] = null;
					$mainArray[$arId]['achieved_points'] = null;
					$mainArray[$arId]['payment_points'] = null;
				}										
				if(!isset($otherSaleMap[$arId]))
					$otherSaleMap[$arId] = null;
				
				$totalOtherSale = $totalOtherSale + $otherSaleMap[$arId];
				$totalSale = $totalSale + $mainArray[$arId]['actual_sale'];
				$totalPoints = $totalPoints + $mainArray[$arId]['points'];
				$totalPaymentPoints = $totalPaymentPoints + $mainArray[$arId]['payment_points'];							?>
				<tr align="center">
					<td style="text-align:left;"><?php echo $arMap[$arId]['name'];?></b></td>
					<td><?php echo $arMap[$arId]['mobile'];?></b></td>
					<td style="text-align:left;"><?php echo $arMap[$arId]['shop'];?></b></td>
					<td><?php echo $target;?></td>
					<td><?php echo $otherSaleMap[$arId];?></td>
					<td><?php echo $mainArray[$arId]['actual_sale'];?></td>
					<td><?php echo $rate;?></td>
					<td><?php echo $mainArray[$arId]['points'];?></td>
					<td><?php echo $mainArray[$arId]['actual_perc'].'%';?></td>
					<td><?php echo $mainArray[$arId]['point_perc'].'%';?></td>
					<!--td><?php //echo $payment_perc;?></td>
					<td><?php //echo $mainArray[$arId]['achieved_points'];?></td-->
					<td><?php echo '<b>'.$mainArray[$arId]['payment_points'].'</b>';?></td>
				</tr>																															<?php
			}																																	?>
			<thead>
				<tr>
					<th style="width:20%;"></th>
					<th style="width:12%;"></th>
					<th style="width:25%;"></th>
					<th><?php echo $totalTarget;?></th>
					<th><?php echo $totalOtherSale;?></th>
					<th><?php echo $totalSale;?></th>
					<th></th>
					<th><?php echo $totalPoints;?></th>
					<th><?php if($totalTarget >0) echo round($totalSale/$totalTarget*100,1); else echo '0'?>%</th>
					<th></th>	
					<!--th></th>	
					<th></th-->
					<th><?php echo $totalPaymentPoints;?></th>
				</tr>	
			</thead>	
		</table>
		<br/><br/><br/><br/>
	</div>
</body>
</html>
<?php
}
else
	header("../Location:index.php");
?>