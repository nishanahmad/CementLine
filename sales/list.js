let rateMap = new Map();
let discountMap = new Map();

$(function(){

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	var totalAmount = urlParams.get('total');
	if(totalAmount)
	{
		$("#totalAmount").text(totalAmount);
		var totalModal = new bootstrap.Modal(document.getElementById('totalModal'), {keyboard: false});
		totalModal.show();
		setTimeout(function() {totalModal.hide()}, 5000);		
	}
	
	$('#client,#client-filter,#eng-filter').select2();
	
	var pickeropts = { dateFormat:"dd-mm-yy"}; 
	$( ".datepicker" ).datepicker(pickeropts);	
	
	$(".maintable").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	}); 
	$(".ratetable").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
	}); 
	
	
	if(window.location.href.includes('success')){
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);					
	}





	// Populate the tables based on SQL and Range from URL
	
	$("#todayFilter").on("click",function(){
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0');
		var yyyy = today.getFullYear();
		today = dd + '-' + mm + '-' + yyyy;
		
		callAjax(today,'Today');
	});				
	
	$("#10DaysFilter").on("click",function(){
		var days10 = new Date(Date.now() - (10 * 24 * 60 * 60 * 1000));
		var dd = String(days10.getDate()).padStart(2, '0');
		var mm = String(days10.getMonth() + 1).padStart(2, '0');
		var yyyy = days10.getFullYear();
		days10 = dd + '-' + mm + '-' + yyyy;
		
		callAjax(days10,'10 Days');
	});				
	
	function callAjax(date,range){
		if(range !== 'Today'){
			$.ajax({
				type: "POST",
				url: "ajax/filterSales.php",
				data:'startDate='+ date,
				success: function(response){
					if(response)
					{
						console.log(response);
						window.location.href = 'list.php?sql='+response+'&range='+range;
					}
					else
						alert('Some error occured. Please contact developer !!!');
				}
			});			
		}
		else{
			$.ajax({
				type: "POST",
				url: "ajax/filterSales.php",
				data:'startDate='+ date + '&endDate=' + date,
				success: function(response){
					if(response)
						window.location.href = 'list.php?sql='+response+'&range='+range;
					else
						alert('Some error occured. Please contact developer !!!');
				}
			});						
		}
	}
	
	$("#customFilter").on("click",function(){
		var filterModal = new bootstrap.Modal(document.getElementById('filterModal'), {})
		filterModal.show();				
	});
				
			
	colCount = document.getElementById('ratetable').rows[1].cells.length;
	if(colCount > 2){
		$('.ratetable').find('tbody tr:visible').each(function(){
			var productName = $(this).find('td:eq(0)').text();
			var rate = $(this).find('td:eq(2)').text();
			var discount = $(this).find('td:eq(3)').text();
			
			rateMap.set(productName,rate);
			discountMap.set(productName,discount);
		});
	}
	
	
			
	// AJAX to navigate to edit page	
	$('.saleId').click(function(){
		var saleId = $(this).data('id');
		var params = $(this).data('params');
		params = params.replace('success&','');
		window.location.href = 'edit.php?id='+saleId + '&' + params;
	});	

				

		
		
	//  SET FINAL RATE ON PAGE LOAD
	var date = $("#date").val();
	var product = $("#product").val();
	var client = $("#client").val();

	$.ajax({
		type: "POST",
		url: "ajax/getRate.php",
		data:'date='+date+'&product='+product,
		success: function(data){
			var rate = data;
			$("#rate").val(rate);
			refreshRate();
		}
	});		
	
	$.ajax({
		type: "POST",
		url: "ajax/getWD.php",
		data:'product='+product+'&date='+date,
		success: function(data){
			$("#wd").val(data);
			refreshRate();
		}
	});
	
	//  REFRESH FINAL RATE ON FIELD CHANGES				
	$("#date").change(function()
	{
		date = $(this).val();
		product = $("#product").val();
		client = $("#client").val();
		
		$.ajax({
			type: "POST",
			url: "ajax/getRate.php",
			data:'date='+date+'&product='+product,
			success: function(data){
				var rate = data;
				$("#rate").val(rate);
				refreshRate();
			}
		});
		$.ajax({
			type: "POST",
			url: "ajax/getWD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#wd").val(data);
				refreshRate();
			}
		});												
		$.ajax({
			type: "POST",
			url: "ajax/getCD.php",
			data:'date='+date+'&product='+product+'&client='+client,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});		
		$.ajax({
			type: "POST",
			url: "ajax/getSD.php",
			data:'date='+date+'&product='+product+'&client='+client,
			success: function(data){
				$("#sd").val(data);
				refreshRate();
			}
		});				
	});


	$("#product").change(function()
	{
		date = $("#date").val();
		product = $(this).val();
		client = $("#client").val();
		
		$.ajax({
			type: "POST",
			url: "ajax/getRate.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				var rate = data;
				$("#rate").val(rate);
				refreshRate();
			}
		});			
		$.ajax({
			type: "POST",
			url: "ajax/getWD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#wd").val(data);
				refreshRate();
			}
		});										
		$.ajax({
			type: "POST",
			url: "ajax/getCD.php",
			data:'product='+product+'&date='+date+'&client='+client,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});				
		$.ajax({
			type: "POST",
			url: "ajax/getSD.php",
			data:'product='+product+'&date='+date+'&client='+client,
			success: function(data){
				$("#sd").val(data);
				refreshRate();
			}
		});						
	});


	$("#client").change(function()
	{
		var date = $("#date").val();
		var product = $("#product").val();
		var client = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajax/getCD.php",
			data:'client='+client+'&date='+date+'&product='+product,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});		
		$.ajax({
			type: "POST",
			url: "ajax/getWD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#wd").val(data);
				refreshRate();
			}
		});												
		$.ajax({
			type: "POST",
			url: "ajax/getSD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#sd").val(data);
				refreshRate();
			}
		});														


		var arId = $('#client').val();
		var shopName = shopNameArray[arId];
		$('#shopName').val(shopName);		
	});	
	

	$("#bd").change(function(){
		refreshRate();
	});		
		
});

function refreshRate()
{
	var rate=document.getElementById("rate").value;
	var cd=document.getElementById("cd").value;
	var wd=document.getElementById("wd").value;
	var bd=document.getElementById("bd").value;
	var bd=document.getElementById("sd").value;
	console.log(rate);
	$('#final').val(rate-cd-wd-bd-sd);
}			

	
let qtyMap = new Map();				
$('.maintable').on('initialized filterEnd', function(){
	qtyMap.clear();
	var qty;
	$(this).find('tbody tr:visible').each(function(){
		var productName = $(this).find('td:eq(2)').text();
		if(qtyMap.has(productName))
			qty = parseFloat( qtyMap.get(productName) ) + parseFloat( $(this).find('td:eq(3)').text() );
		else
			qty = parseFloat( $(this).find('td:eq(3)').text() );
		
		qtyMap.set(productName,qty);
	});
	$("#ratebody").empty();
	for (let [key, value] of qtyMap){
		var table = document.getElementById("ratetable");
		var tableRef = document.getElementById('ratetable').getElementsByTagName('tbody')[0];
		var row   = tableRef.insertRow();					
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		cell1.innerHTML = `${key}`;
		cell2.innerHTML = `${value}`;
		if(colCount >2)
		{
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			cell3.innerHTML = rateMap.get(`${key}`);
			cell4.innerHTML = discountMap.get(`${key}`);
		}
	}
})	 