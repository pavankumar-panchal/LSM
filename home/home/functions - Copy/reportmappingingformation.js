// JavaScript Document

function districtselect(selectid,comparevalue)
{
	var code = $('#form_state').val();
	var outputselect = $('#districtdiv');
	var passdata = "&submittype=getdistrict&code=" + code +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reportmappinginformation.php";
	ajaxobjext97 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			outputselect.html(response); 
				if(selectid && comparevalue)
					autoselect(selectid,comparevalue);
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});	
	$('#regiondiv').html('<select name="form_region" id="form_region"><option value = "">- - - - ALL - - - -</option></select>');
}


function regionselect(bypasscode,selectid,comparevalue)
{
	var code;
	if(bypasscode)
		code = bypasscode;
	else
		code = $('#form_district').val();
	var outputselect = $('#regiondiv');
	var passdata = "&submittype=getsubdistrict&code=" + code +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reportmappinginformation.php";
	ajaxobjext98 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			outputselect.html(response); 
				if(selectid && comparevalue)
					autoselect(selectid,comparevalue);
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});	
}

// Function to dispaly all mapping details(default)

function showdefault(type)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata);
	var queryString = "../ajax/reportmappinginformation.php"; 
	ajaxobjext99 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^'); //alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$("#tabgroupgridc1_1").html(ajaxresponse[1]);
				$("#getmorelink").html(ajaxresponse[2]);
				$("#totalcount").html('   ' + ajaxresponse[3]+' '+ "Records");
				$("#gridprocess").html('');
			}
			else
			{
				$("#gridprocess").html(scripterror1());
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});	
}

function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var queryString = "../ajax/reportmappinginformation.php"; 
	ajaxobjext100 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^'); //alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$("#gridprocess").html('');
				$('#resultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html( $('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
				$('#getmorelink').html(ajaxresponse[2]);
				$('#totalcount').html('   ' + ajaxresponse[3]+' '+ "Records");
			}
			else
			{
				$("#gridprocess").html('');
				$("#getmorelink").html(errormessage("No datas found to be displayed."));
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#msg_box").html(scripterror());
		}
	});	
} 


function filter(command)
{
	var myform = $('#mappinginformationform');
	var message = $('#message'); 
	if(command == 'toexcel')
	{
		$('#mappinginformationform').attr('action','mappinginformationtoexcel.php');
		myform.submit();
	}
	else
	{
		var radiovalue = $("input[name='dealercompany']:checked").val();
		var orderby = $("input[name='orderby']:checked").val();
		var searchtext =$("#companyname").val(); //alert(searchtext)
		var category = $("#category").val();//alert(category)
		var state = $("#form_state").val();//alert(state)
		var district = $("#form_district").val();//alert(district)
		var region = $("#form_region").val();//alert(region)
		var disabled = $("#disabled").val();
		$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortfilterajaxprocess(\'initial\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&searchtext="+searchtext+"&category="+category+"&state="+state+"&district="+district+"&region="+region+"&disabled="+disabled+"&radiovalue="+radiovalue+"&orderby="+orderby+"&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata);
		var queryString = "../ajax/reportmappinginformation.php"; 
		ajaxobjext101 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var ajaxresponse = response.split('^');//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]);
					$("#totalcount").html('   ' + ajaxresponse[3]+' '+ "Records");
					$("#gridprocess").html('');
				}
				else
				{
					$("#gridprocess").html(scripterror1());
				}
			}, 
			error: function(a,b)
			{
				$("#gridprocess").html(scripterror1());
			}
		});	
	}	
}

function getmorerecordsofsearch(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortfilterajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
	var myform = $('#mappinginformationform');
	var radiovalue = $("input[name='dealercompany']:checked").val();
	var orderby = $("input[name='orderby']:checked").val();
	var searchtext = $("#companyname").val(); //alert(searchtext)
	var category = $("#category").val();//alert(category)
	var state = $("#form_state").val();//alert(state)
	var district = $("#form_district").val();//alert(district)
	var region = $("#form_region").val();//alert(region)
	var disabled = $("#disabled").val();
		
	var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&searchtext="+searchtext+"&category="+category+"&state="+state+"&district="+district+"&region="+region+"&disabled="+disabled+"&radiovalue="+radiovalue+"&orderby="+orderby+"&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata)
	var queryString = "../ajax/reportmappinginformation.php"; 
	ajaxobjext102 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^'); //alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$("#gridprocess").html('');
				$('#resultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html( $('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
				$('#getmorelink').html(ajaxresponse[2]);
				$('#totalcount').html('   ' + ajaxresponse[3]+' '+ "Records");
			}
			else
			{
				$("#gridprocess").html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#msg_box").html(scripterror());
		}
	});	
}


function abortfilterajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext101.abort();
		$("#gridprocess").html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext102.abort();
		$("#gridprocess").html('');
	}
}