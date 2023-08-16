// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reportdealers.php";
	ajaxobjext91 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response1 = response.split("|^|");
			if(response1[0] == '1')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html(' => [Total number of dealers (' + response1[3] +' Records)]');
			}
			else if(response1[0] == '2')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html(' => [Total number of dealers (' + response1[3] +' Records)]');
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

function filtering(command)
{
	var form = $("#filterform");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var msg_box = $("#msg_box");
	
	if(command == 'excel')
		form.submit();
	else
	{
		$("#hiddensearchcriteria").val(textfield);
		$("#hiddendatabasefield").val(subselection);
		var disabled = $('#disabled').val();
		$("#gridprocess").html(processing() +'  '+ '<span onclick = "abortdealerfilterreportajaxprocess(\'initial\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&searchtext=" + textfield + "&subselection=" + subselection + "&disabled=" + disabled+"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/reportdealers.php";
		ajaxobjext92 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var response = response.split("|^|");
				if(response[0] == '1')
				{
					$("#tabgroupgridc1_1").html(response[1]);
					$("#getmorelink").html(response[2]);
					$("#gridprocess").html(' => Filter Applied (' + response[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]');
				}
				else if(response[0] == '2')
				{
					$("#tabgroupgridc1_1").html(response[1]);
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


function getmorerecords(startlimit,slnocount,showtype,type)
{
	var form = $("#filterform");
	var textfield = $("#hiddensearchcriteria").val();
	var subselection = $("#hiddendatabasefield").val();
	var disabled = $('#disabled').val();
	
	if(type == 'dealerlist')
	{
		$("#gridprocess").html(processing());
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'view')
	{
		$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortdealerfilterreportajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&searchtext=" + textfield + "&subselection=" + subselection +"&disabled=" + disabled+"&dummy=" + Math.floor(Math.random()*10230000000)
	}  //alert(passdata);
	var queryString = "../ajax/reportdealers.php";
	ajaxobjext93 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$('#resultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
				$('#getmorelink').html(ajaxresponse[2]);
				$("#gridprocess").html(' => Filter Applied (' + ajaxresponse[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]');
			}
			else if(ajaxresponse[0] == '2')
			{
				$('#resultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
				$('#getmorelink').html(ajaxresponse[2]);
				$("#gridprocess").html(' => Filter Applied (' + ajaxresponse[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]');
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

function abortdealerfilterreportajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext92.abort();	
		$("#gridprocess").html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext93.abort();
		$("#gridprocess").html('');
	}
}