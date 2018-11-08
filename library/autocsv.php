<?php
function auto_csv($results)
{
	$path = "D:\\";	
    $name = $path.date("M-Y").'.csv';
	
	$fp = fopen($name, 'w');
    
	foreach($results as $result)
    {
        fputcsv($fp, $result);
    }

    fclose($fp);
}
?>