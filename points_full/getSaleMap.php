<?php
function getSaleMap($arIds,$startYear,$endYear)
{
	require '../connect.php';
		
	$saleMap = array();	
	for($year=$startYear; $year<=$endYear; $year++)
	{
		$sales = mysqli_query($con,"SELECT client,
									  sum(if(month(date) = 1, IFNULL(qty, 0), 0))  AS '1',
									  sum(if(month(date) = 2, IFNULL(qty, 0), 0))  AS '2',
									  sum(if(month(date) = 3, IFNULL(qty, 0), 0))  AS '3',
									  sum(if(month(date) = 4, IFNULL(qty, 0), 0))  AS '4',
									  sum(if(month(date) = 5, IFNULL(qty, 0), 0))  AS '5',
									  sum(if(month(date) = 6, IFNULL(qty, 0), 0))  AS '6',
									  sum(if(month(date) = 7, IFNULL(qty, 0), 0))  AS '7',
									  sum(if(month(date) = 8, IFNULL(qty, 0), 0))  AS '8',
									  sum(if(month(date) = 9, IFNULL(qty, 0), 0))  AS '9',
									  sum(if(month(date) = 10, IFNULL(qty, 0), 0)) AS '10',
									  sum(if(month(date) = 11, IFNULL(qty, 0), 0)) AS '11',
									  sum(if(month(date) = 12, IFNULL(qty, 0), 0)) AS '12'
										FROM sales WHERE YEAR(date) = '$year' 
										AND client IN('$arIds') AND (product = 63 OR product = 76)
										GROUP BY client")  or die(mysqli_error($con));		 
		foreach($sales as $sale)
		{
			for($month=1;$month<=12;$month++)
			{
				if(isset($sale[$month]))
					$saleMap[$sale['client']][$year][$month] = (int)$sale[$month];	
				else
					$saleMap[$sale['client']][$year][$month] = 0;	
			}
		}			
	}

	return $saleMap;
}
?>	