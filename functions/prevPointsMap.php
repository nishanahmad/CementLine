<?php
function getPrevPoints($arList,$endYear,$endMonth)
{
	require '../connect.php';
	
	$startYear = 2018; 
	$startMonth = 1;
	
	foreach($arList as $arId)
	{
		$arMap[$arId]['prevPoints'] = 0;	
		$arMap[$arId]['prevRedemption'] = 0;			
	}
	
	$arIds = implode("','",array_keys($arMap));	
	
	
	//call targetMap and saleMap from helper functions 
	
	$targetMap = getTargetMap($arIds,$startYear);		// arId => year => month => target
	$saleMap = getSaleMap($arIds,$startYear,$endYear);	    // arId => year => month = sale
	$extraBagsMap = getExtraBagsMap($arIds,$startYear,$endYear);	

	// Add points based on monthly targets
	foreach($targetMap as $arId => $yearMonthArray)
	{
		foreach($yearMonthArray as $year => $monthArray)
		{
			if($year <= $endYear)
			{
				foreach($monthArray as $month => $detailArray)
				{
					if( $year < $endYear || ($month < $endMonth && $year == $endYear))
					{
						if(isset($saleMap[$arId][$year][$month]))
						{
							$sale = $saleMap[$arId][$year][$month];
							if(isset($extraBagsMap[$arId][$year][$month]))	
								$sale = $sale + $extraBagsMap[$arId][$year][$month];
						}	
						else
							$sale = 0;
						
						$points = round($sale * $detailArray['rate'],0);
						$actual_perc = round($sale * 100 / $detailArray['target'],0);
						$point_perc = getPointPercentage($actual_perc,$year,$month);			
						$achieved_points = round($points * $point_perc/100,0);
						
						if($sale > 0)		
						{
							$payment_points = round($achieved_points * $detailArray['payment_perc']/100,0);
							if(isset($extraBagsMap[$arId][$year][$month]))	
								$payment_points = $payment_points - $extraBagsMap[$arId][$year][$month];
								
						}	
						else if(isset($detailArray))
							$payment_points = 0;			
						
						$arMap[$arId]['prevPoints'] = $arMap[$arId]['prevPoints'] + $payment_points;												
					}
				}				
			}
		}
	}
	
	$redemptionList = mysqli_query($con,"SELECT client,SUM(points) FROM redemption WHERE  ( (YEAR(date) = '$endYear' AND MONTH(date) < '$endMonth') OR (YEAR(date) < '$endYear')) AND client IN('$arIds') GROUP BY client") or die(mysqli_error($con));		 	
	foreach($redemptionList as $redemption)
	{
		$arMap[$redemption['client']]['prevRedemption'] = $arMap[$redemption['client']]['prevRedemption'] + $redemption['SUM(points)'];			
	}

	return $arMap;
}
?>