<?php


function getTargets($year,$arId)
{
	require '../connect.php';
	
	$targetMap = array();
	$targetObjects = mysqli_query($con,"SELECT month, target, payment_perc,rate FROM target WHERE Year='$year' AND client = '$arId' ") or die(mysqli_error($con));		 
	foreach($targetObjects as $target)
	{
		$targetMap[$target['month']]['target'] = $target['target'];
		$targetMap[$target['month']]['rate'] = $target['rate'];
		$targetMap[$target['month']]['payment_perc'] = $target['payment_perc'];
	}
			
	return $targetMap;
}


function getRedemptions($year,$arId)
{
	require '../connect.php';
	
	$redemptionMap = array();
	$redemptionObjects = mysqli_query($con,"SELECT * FROM redemption WHERE YEAR(date)='$year' AND client = '$arId' ") or die(mysqli_error($con));		 
	foreach($redemptionObjects as $redemption)
	{
		$redMonth = (int)date('m',strtotime($redemption['date']));
		$redemptionMap[$redMonth][] = $redemption;
	}
			
	return $redemptionMap;
}


function getSales($year,$arId)
{
	require '../connect.php';
	
	$saleMap = array();	
	$salesList = mysqli_query($con, "SELECT SUM(qty),MONTH(date) FROM sales WHERE YEAR(date) = '$year' AND client = '$arId' AND product = 63 GROUP BY MONTH(date) ORDER BY MONTH(date) ASC" ) or die(mysqli_error($con));
	foreach($salesList as $sale) 
	{
		$saleMap[$sale['MONTH(date)']] = $sale['SUM(qty)'];
	}
			
	return $saleMap;
}


function getExtraBags($year,$arId)
{
	require '../connect.php';
	
	$extraBagsMap = array();	
	$extraBagsList = mysqli_query($con, "SELECT SUM(qty),MONTH(date) FROM extra_bags WHERE YEAR(date) = '$year' AND client = '$arId' AND product = 63 GROUP BY MONTH(date) ORDER BY MONTH(date) ASC" ) or die(mysqli_error($con));
	foreach($extraBagsList as $extraBags) 
	{
		$extraBagsMap[$extraBags['MONTH(date)']] = $extraBags['SUM(qty)'];
	}
			
	return $extraBagsMap;
}


function getPoints($year,$saleMap,$extraBagsMap,$isActive,$targetMap)
{
	require '../connect.php';
	require 'targetFormula.php';
	
	$pointsMap = array();
	foreach($saleMap as $month => $total)
	{
		if(isset($extraBagsMap[$month]))
			$total = $total + $extraBagsMap[$month];
		
		$pointsMap[$month]['points'] = null;
		$pointsMap[$month]['actual_perc'] = null;
		$pointsMap[$month]['point_perc'] = null;
		$pointsMap[$month]['achieved_points'] = null;
		$pointsMap[$month]['payment_points'] = null;					

		if(isset($targetMap[$month]['target']) && $isActive && $targetMap[$month]['target'] >0)
		{
			$points = round($total * $targetMap[$month]['rate'],0);
			$actual_perc = round($total * 100 / $targetMap[$month]['target'],0);
			$point_perc = getPointPercentage($actual_perc,$year,$month);			 
			$achieved_points = round($points * $point_perc/100,0);
			
			if($total > 0)		
			{
				$payment_points = round($achieved_points * $targetMap[$month]['payment_perc']/100,0);				
				if(isset($extraBagsMap[$month]))
					$payment_points = $payment_points - $extraBagsMap[$month];
			}
			else
				$payment_points = 0;			

			$pointsMap[$month]['points'] = $points;
			$pointsMap[$month]['actual_perc'] = $actual_perc;
			$pointsMap[$month]['point_perc'] = $point_perc;
			$pointsMap[$month]['achieved_points'] = $achieved_points;
			$pointsMap[$month]['payment_points'] = $payment_points;			
		}		
	}
			
	return $pointsMap;
}


function getOpeningPoints($year,$arId,$isActive)
{
	require '../connect.php';
	
	$opening = 0;
	if($year == 2018)
	{
		$redemptionObjects = mysqli_query($con,"SELECT * FROM redemption WHERE month(date)<10 AND client = '$arId' ") or die(mysqli_error($con));		 
		foreach($redemptionObjects as $redemption)
		{
			$opening = $opening - $redemption['points'];
		}
	}
	else if($year > 2018)
	{
		$i = $year-1;
		while($i >= 2018)
		{
			$targetMap = getTargets($year,$arId);
			$redemptionMap = getRedemptions($year,$arId);
			$saleMap = getSales($year,$arId);
			
			foreach($saleMap as $month => $total)
			{
				if(isset($targetMap[$month]['target']) && $isActive && $targetMap[$month]['target'] >0)
				{
					$points = round($total * $targetMap[$month]['rate'],0);
					$actual_perc = round($total * 100 / $targetMap[$month]['target'],0);
					$point_perc = getPointPercentage($actual_perc,$year,$month);			 
					$achieved_points = round($points * $point_perc/100,0);
					
					if($total > 0)		
						$payment_points = round($achieved_points * $targetMap[$month]['payment_perc']/100,0);
					else
						$payment_points = 0;			
					
					$opening = $opening + $payment_points;
				}		
			}			
			
			foreach($redemptionMap as $redemption)
			{
				$opening = $opening - $redemption['points'];
			}
			
			$i--;
		}
	}
			
	return $opening;
}


?>