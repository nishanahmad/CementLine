<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../functions/targetFormula.php';
	require '../functions/prevPointsMap.php';
	require 'getExtraBagsMap.php';
	require 'getTargetMap.php';
	require 'getSaleMap.php';	
	
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
	
	$arObjects =  mysqli_query($con,"SELECT id,name,mobile,shop FROM clients WHERE isActive = 1 ORDER BY name ASC ") or die(mysqli_error($con));		 
	foreach($arObjects as $ar)
	{
		$arMap[$ar['id']]['name'] = $ar['name'];
		$arMap[$ar['id']]['mobile'] = $ar['mobile'];
		$arMap[$ar['id']]['shop'] = $ar['shop'];
	}				
	
	$prevMap = getPrevPoints(array_keys($arMap),$year,$month);
	
	$arIds = implode("','",array_keys($arMap));
	

	$targetObjects = mysqli_query($con,"SELECT client, target, payment_perc,rate FROM target WHERE  month = '$month' AND Year='$year' AND target > 0 AND client IN('$arIds')") or die(mysqli_error($con));		 
	foreach($targetObjects as $target)
	{
		$targetMap[$target['client']]['target'] = $target['target'];
		$targetMap[$target['client']]['rate'] = $target['rate'];
		$targetMap[$target['client']]['payment_perc'] = $target['payment_perc'];
	}
	
	$coromandelProducts =  mysqli_query($con,"SELECT * FROM products WHERE brand = (SELECT id FROM brands WHERE name = 'COROMANDEL')") or die(mysqli_error($con));		 
	foreach($coromandelProducts as $coromandelProduct)
		$coromandelMap[$coromandelProduct['id']] = null;
	
	$corIds = implode("','",array_keys($coromandelMap));	
	
	$sales = mysqli_query($con,"SELECT client,SUM(qty),YEAR(date),MONTH(date) FROM sales WHERE '$year' = YEAR(date) AND '$month' = MONTH(date) AND product IN('$corIds') AND client IN ('$arIds') GROUP BY client") or die(mysqli_error($con));	
	foreach($sales as $sale)
	{
		$arId = $sale['client'];
		$extraBagsMap = getExtraBagsMap($arId,$sale['YEAR(date)'],$sale['YEAR(date)']);

		$total = $sale['SUM(qty)'];
		if(isset($extraBagsMap[$arId][$year][$month]))
			$total = $total + $extraBagsMap[$arId][$year][$month];
		
		if($arId == 192)
			var_dump($total);	
		
		if(isset($targetMap[$arId]))
		{
			$points = round($total * $targetMap[$arId]['rate'],0);
			$actual_perc = round($total * 100 / $targetMap[$arId]['target'],0);
			$point_perc = getPointPercentage($actual_perc,$year,$month);			
			$achieved_points = round($points * $point_perc/100,0);
			
			if($total > 0)		
			{
				$payment_points = round($achieved_points * $targetMap[$arId]['payment_perc']/100,0);
				if(isset($extraBagsMap[$arId][$year][$month]))
					$payment_points = $payment_points - $extraBagsMap[$arId][$year][$month];
			}
			else
				$payment_points = 0;			
			
			$pointMap[$arId]['points'] = $payment_points;			
		}
		else
		{
			$pointMap[$arId]['points'] = 0;
		}	
	}			
	
	$currentRedemption = mysqli_query($con,"SELECT client,SUM(points) FROM redemption WHERE '$year' = year(`date`) AND '$month' = month(`date`) AND client IN ('$arIds') GROUP BY client") or die(mysqli_error($con));	
	foreach($currentRedemption as $redemption)
	{
		$redemptionMap[$redemption['client']] = $redemption['SUM(points)'];
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
<script src="../js/fileSaver.js"></script>
<script src="../js/tableExport.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script> 
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
			$monthList = mysqli_query($con, "SELECT DISTINCT month FROM target ORDER BY month ASC" ) or die(mysqli_error($con));	
			foreach($monthList as $monthObj) 
			{	
	?>			<option value="<?php echo $monthObj['month'];?>" <?php if($monthObj['month'] == $month) echo 'selected';?>><?php echo getMonth($monthObj['month']);?></option>															<?php	
			}
	?>	</select>					
			&nbsp;&nbsp;

		<select id="jsYear" name="jsYear" class="textarea" onchange="return rerender();">																				<?php	
			$yearList = mysqli_query($con, "SELECT DISTINCT year FROM target ORDER BY year DESC") or die(mysqli_error($con));	
			foreach($yearList as $yearObj) 
			{
?>				<option value="<?php echo $yearObj['year'];?>" <?php if($yearObj['year'] == $year) echo 'selected';?>><?php echo $yearObj['year'];?></option>																			<?php	
			}
?>		</select>
		
		<br><br>
		
		<img src="../images/excel.png" id="button" height="50px" width="45px" />
		<br/><br/>

		<table id="Points" class="responstable" style="width:70% !important">
		<thead>
			<tr>
				<th style="width:25%;text-align:left;">AR</th>
				<th style="width:12%;">Mobile</th>
				<th style="width:25%;text-align:left;">Shop</th>
				<th>Opng Pnts</th>
				<th>Current Pnts</th>	
				<th>Redeemed Pnts</th>	
				<th>Balance</th>	
			</tr>
		</thead>																										<?php
		
			$openingTotal = 0;
			$currentTotal = 0;
			$redeemedTotal = 0;
			$balanceTotal = 0;
			
			foreach($arMap as $arId => $detailMap)
			{		
				if(!isset($targetMap[$arId]))
					$targetMap[$arId]['target'] = 0;						
				if(!isset($pointMap[$arId]))	
					$pointMap[$arId]['points'] = 0;
				if(!isset($redemptionMap[$arId]))	
					$redemptionMap[$arId] = 0;																																	?>
				
				
				<tr align="center">
					<td style="text-align:left;"><?php echo $detailMap['name'];?></b></td>
					<td><?php echo $detailMap['mobile'];?></b></td>
					<td style="text-align:left;"><?php echo $detailMap['shop'];?></b></td>
					<td><?php echo $prevMap[$arId]['prevPoints'] - $prevMap[$arId]['prevRedemption'];?></b></td>
					<td><?php echo $pointMap[$arId]['points'];?></td>
					<td><?php echo $redemptionMap[$arId];?></td>
					<td><?php echo $prevMap[$arId]['prevPoints'] - $prevMap[$arId]['prevRedemption'] + $pointMap[$arId]['points'] - $redemptionMap[$arId];?></td>
				</tr>																																							<?php
				$openingTotal = $openingTotal + $prevMap[$arId]['prevPoints'] - $prevMap[$arId]['prevRedemption'];
				$currentTotal = $currentTotal + $pointMap[$arId]['points'];
				$redeemedTotal = $redeemedTotal + $redemptionMap[$arId];
				$balanceTotal = $balanceTotal + $prevMap[$arId]['prevPoints'] - $prevMap[$arId]['prevRedemption'] + $pointMap[$arId]['points'] - $redemptionMap[$arId];
			}																																									?>
		<thead>
			<tr>
				<th style="width:25%;text-align:left;"></th>
				<th style="width:12%;"></th>
				<th style="width:25%;text-align:left;"></th>
				<th><?php echo $openingTotal;?></th>
				<th><?php echo $currentTotal;?></th>	
				<th><?php echo $redeemedTotal;?></th>	
				<th><?php echo $balanceTotal;?></th>	
			</tr>
		</thead>																													
		</table>
		<br/><br/><br/><br/>
	</div>
</body>
</html>																																											<?php
}
else
	header("../Location:index.php");
?>