<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>	
<!DOCTYPE html>
<html>
	<title>Sales List</title>
	<head>
	<style>
		.floatLeft { width: 50%; float: left; }
		.floatRight {width: 50%; float: right; }
		.container { overflow: hidden; }	
	
		.responstable {	
		  width: 80%;
		  overflow: hidden;
		  background: #FFF;
		  color: #024457;
		  border-radius: 5px;
		  border: 1px solid #167F92;
		  border-spacing: 1px;
		}
		.responstable tr {
		  border: 1px solid #D9E4E6;
		}
		.responstable tr:nth-child(odd) {
		  background-color: #EAF3F3;
		}
		.responstable th {
		  display: none;
		  border: 1px solid #FFF;
		  background-color: #167F92;
		  color: #FFF;
		  padding: 1em;
		 }
		.responstable td {
		  display: block;
		  word-wrap: break-word;
		}
		.responstable td:first-child {
		  display: table-cell;
		  border-right: 1px solid #D9E4E6;
		}
		@media (min-width: 480px) {
		  .responstable td {
			border: .5px solid #D9E4E6;
		  }
		}
		.responstable th, .responstable td {
		  text-align: left;
		  margin: .5em 1em;
		}
		@media (min-width: 480px) {
		  .responstable th, .responstable td {
			display: table-cell;
			padding: .2em;
		  }
		}
	</style>
		<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
		
		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					dom: 'lfBrtip',
					buttons: ['excelHtml5','colvis'],									
					"processing": true,
					"serverSide": true,
					"responsive": true,
					"bJQueryUI":true,
					"iDisplayLength": 2000,					
					"ajax":{
						url :"list_server.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
										}
						   }
				} );
				
   dataTable.on( 'xhr', function () {
    var json = dataTable.ajax.json();
	var itemString1 = '<table class="responstable"><tr><th>Cement</th><th>Quantity</th></tr><td>' + json.itemarray1;
	itemString1 = itemString1.replace(/"/g,'');
	itemString1 = itemString1.replace(/:/g,'</td><td>');	
	itemString1 = itemString1.replace(/,/g,'</td></tr><td>');	
	itemString1 = itemString1 + '</td></tr></table>';
	itemString1 = itemString1.replace(/{/g,'');	
	itemString1 = itemString1.replace(/}/g,'');	
	//console.log(itemString1);
	$('.itemarray1').html(itemString1);
	
	var itemString2 = '<table class="responstable"><tr><th>Cement</th><th>Quantity</th></tr><td>' + json.itemarray2;
	itemString2 = itemString2.replace(/"/g,'');
	itemString2 = itemString2.replace(/:/g,'</td><td>');	
	itemString2 = itemString2.replace(/,/g,'</td></tr><td>');	
	itemString2 = itemString2 + '</td></tr></table>';
	itemString2 = itemString2.replace(/{/g,'');	
	itemString2 = itemString2.replace(/}/g,'');	
	//console.log(itemString2);
	$('.itemarray2').html(itemString2);
	
	$('.total').html(json.total);	
	
	} );				
				
	$("#employee-grid_filter").css("display","none");  // hiding global search box
	$('.search-input-text').on( 'keyup click', function () {   // for text boxes
		var i =$(this).attr('data-column');  // getting column index
		var v =$(this).val();  // getting search input value
		dataTable.columns(i).search(v).draw();
	} );
	$('.search-input-select').on( 'change', function () {   // for select box
		var i =$(this).attr('data-column');  
		var v =$(this).val();  
		dataTable.columns(i).search(v).draw();
	} );
				
} );
		</script>

	</head>
	<body>
		<div align="center">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
					<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
		
		</div>
<div align="center" class="gradient">
<font size=5>
<br>
<div class="container">
<div class="floatLeft">
<div class='itemarray1'></div>
</div>
<div class="floatRight">
<div class='itemarray2'></div>
</div>
</div>
<br>
<b>TOTAL : <span class='total'></span>
</b></font>
		<br><br><br>
			<input type="text" data-column="0"  style="width:50px" class="search-input-text textarea" placeholder="Id">&nbsp&nbsp
			<input type="text" data-column="1"  style="width:120px" class="search-input-text textarea" placeholder="Date">&nbsp&nbsp
			<input type="text" data-column="2"  class="search-input-text textarea" placeholder="AR">&nbsp&nbsp
			<input type="text" data-column="3"  class="search-input-text textarea" placeholder="Truck">&nbsp&nbsp
			<input type="text" data-column="4"  class="search-input-text textarea" placeholder="Product">&nbsp&nbsp
			<input type="text" data-column="5"  style="width:50px" class="search-input-text textarea" placeholder="Qty">&nbsp&nbsp
			<input type="text" data-column="6"  class="search-input-text textarea" placeholder="Bill No.">&nbsp&nbsp
			<input type="text" data-column="7"  class="search-input-text textarea" placeholder="Customer">&nbsp&nbsp

		<br><br>
			<table id="employee-grid" class="display cell-border no-wrap" >
					<thead>
						<tr>
							<th>Id</th>
							<th style="min-width:80px !important">Date</th>
							<th>AR</th>
							<th>Truck</th>
							<th>Product</th>
							<th>Qty</th>
							<th>BILL NO</th>							
							<th>CSTMR NAME</th>							
							<th>Remarks</th>							
						</tr>
					</thead>
			</table>
		</div>
	</body>
</html>
<?php
}
else
	header("Location:../index.php");

?>
	