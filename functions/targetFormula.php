<?php
function getPointPercentage($actual_perc,$year,$month)
{
	if($year == 2018 && $month <= 11)
	{
		if($actual_perc <= 70)			
			$point_perc = 0;
		else if($actual_perc <= 80)		
			$point_perc = 50;
		else if($actual_perc <= 95)		
			$point_perc = 70;
		else if($actual_perc >= 96)		
			$point_perc = 100;										
	}
	else
	{
		if($actual_perc <= 49)			
			$point_perc = 0;
		else if($actual_perc <= 69)		
			$point_perc = 50;
		else
			$point_perc = 100;										
	}
			
	return $point_perc;
}
?>