<?php
require 'connect.php';
require 'library/csv.php';
  
$fromdate = date("Y-m-d", strtotime($_POST['from']));
$todate = date("Y-m-d", strtotime($_POST['to']));;

$query_cement = "SELECT * FROM cement_details order by cement_name";
$result_cement = mysqli_query($con, $query_cement )or die(mysqli_error($con));; 
while($row=mysqli_fetch_array($result_cement,MYSQLI_ASSOC))
{
      $explode_array = explode(' ', $row["cement_name"]);
      $cement = strtolower($explode_array[0]);
      if(!in_array($cement, $cement_names))
      {
          $cement_names[] = $cement;
      }     

}     
//var_dump($cement_names);

$query = "SELECT * FROM sales_entry order by ar";
$result = mysqli_query($con, $query )or die(mysqli_error($con));; 

      $mainarray = array();
      $download_array = array();
      $download_array[] = array('AR NAME');
      
      foreach($cement_names as $key=>$value)
      {
        $download_array[0][] = $value;    
      }    
      //var_dump($download_array);
      
      while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
      {
              if ($row["entry_date"] >= $fromdate  && $row["entry_date"] <= $todate)
              {


              }


      }
                      $mainarray[] = array($arname,$deccan,$shankar,$chettinad,$dalmia,$ultratech,$malabar,$mahashakthi,$bharathi,$sum);

          download_csv($download_array);  







?>	
 