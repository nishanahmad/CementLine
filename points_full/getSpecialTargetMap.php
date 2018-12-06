<?php
function getSpecialTargetMap($arIds,$endDate)
{
	require '../connect.php';
	
	$specialTargetMap = array();
	
	$specialTargetObjects = mysqli_query($con,"SELECT client, fromDate, toDate,target FROM special_target WHERE  toDate <= '$endDate' AND fromDate >= '2018-01-01' AND target >0 AND client IN('$arIds')") or die(mysqli_error($con));		 
	foreach($specialTargetObjects as $specialTarget)
	{
		$specialTargetMap[$specialTarget['client']][$specialTarget['fromDate']] = $specialTarget['target'];
	}	

	return $specialTargetMap;
}
?>	