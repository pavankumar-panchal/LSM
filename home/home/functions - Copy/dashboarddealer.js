// JavaScript Document

function loadtimeexec()
{
	dealerdataretrive();
}

function dealerdataretrive()
{
	var datachartperiod = $("#datachartperiod").val();
	$("#datachartprocess").html(processing());
	var passdata ="&submittype=dealerdatachart&datachartperiod=" + datachartperiod +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/dashboarddealer.php";
	ajaxobjext50 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response1 = response.split("|^|");
			if(response1[0] == 'done')
			{
				$("#spp1").html(response1[1]);
				$("#spp2").html(response1[2]);
				$("#spp3").html(response1[3]);
				$("#spp4").html(response1[4]);
				$("#spp5").html(response1[5]);
				$("#spp6").html(response1[6]);
				$("#spp7").html(response1[7]);
				$("#spp8").html(response1[8]);
				$("#spp9").html(response1[9]);
				$("#spp10").html(response1[10]);
				$("#sto1").html(response1[11]);
				$("#sto2").html(response1[12]);
				$("#sto3").html(response1[13]);
				$("#sto4").html(response1[14]);
				$("#sto5").html(response1[15]);
				$("#sto6").html(response1[16]);
				$("#sto7").html(response1[17]);
				$("#sto8").html(response1[18]);
				$("#sto9").html(response1[19]);
				$("#sto10").html(response1[20]);
				$("#others1").html(response1[21]);
				$("#others2").html(response1[22]);
				$("#others3").html(response1[23]);
				$("#others4").html(response1[24]);
				$("#others5").html(response1[25]);
				$("#others6").html(response1[26]);
				$("#others7").html(response1[27]);
				$("#others8").html(response1[28]);
				$("#others9").html(response1[29]);
				$("#others10").html(response1[30]);
			}
			$("#datachartprocess").html('') ;
		}, 
		error: function(a,b)
		{
			$("#datachartprocess").html(scripterror());
		}
	});		
}