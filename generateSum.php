<?php
$con=mysqli_connect("localhost","nishan","123","cementline");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  
  $fromdate = date("Y-m-d", strtotime($_POST['from']));
  $todate = date("Y-m-d", strtotime($_POST['to']));;

  $query = "SELECT * FROM sales_entry order by ar";
  
 $result = mysqli_query($con, $query ); 
						
     		if ( false===$result ) 
		{
		printf("error: %s\n", mysqli_error($con));
		}
                
	$cement_names = array();	
	
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		if ($row["entry_date"] >= $fromdate  && $row["entry_date"] <= $todate)
		{
                        if (array_key_exists(strtoupper($row["cement"]),$cement_names))
                        {   
                            $cement_names[strtoupper($row["cement"])] = $cement_names[strtoupper($row["cement"])] + $row["qty"];
                        }	
                        else
                        {
                            $cement_names[strtoupper($row["cement"])] = $row["qty"];
                        }

		}
        }
        ksort($cement_names);
        //var_dump($cement_names);
        $brand_names = array();
        foreach($cement_names as $cement=>$qty)
        {
            $explode_array = explode(' ', $cement);
            $brand = strtoupper($explode_array[0]);
            if(array_key_exists($brand,$brand_names))
                $brand_names[$brand] = $brand_names[$brand] + $qty;
            else
                $brand_names[$brand] = $qty;
        }    
        //var_dump($brand_names);
        
                        
?>      	
<html>
<div align="center">
<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;    
<link rel="stylesheet" type="text/css" href="css/table.css" /> 

<table  border="1" width="25%">
<?php                    foreach($brand_names as $brand=>$qty)
                        {
?>
                            <tr>
                            <td align="center"><?php echo $brand;?></td><td align="center"><?php echo $qty;?></td>
<?php                       	
?>                          <tr>
<?php			}    
?>
<table  class=hor-minimalist-b>
<?php                    foreach($cement_names as $cement=>$qty)
                        {
?>
                            <tr>
                            <td align="center"><?php echo $cement;?></td><td align="center"><?php echo $qty;?></td>
<?php                       	
?>                          <tr>
<?php			}    
        
        
            


?>	
 