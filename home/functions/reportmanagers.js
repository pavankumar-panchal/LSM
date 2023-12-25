// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reportmanagers.php";
	ajaxobjext94 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{	
				var response1 = response.split("|^|");
				if(response1[0] == '1')
				{
					$("#tabgroupgridc1_1").html(response1[1]);
					$("#getmorelink").html(response1[2]);					
					$("#gridprocess").html(' => [Total number of Managers (' + response1[3] +' Records)]');
				}
				else
				{
					$("#gridprocess").html(scripterror1());
				}
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
	var subselection =  $("input[name='databasefield']:checked").val();
	if(command == 'excel')
		form.submit();
	else
	{
		$("#hiddensearchcriteria").val(textfield); 
		$("#hiddendatabasefield").val(subselection);
		var managedarea = $("#managedarea").val(); //alert(managedarea)
		var disablelogin = $("#disablelogin").val();//alert(disablelogin) 
		$("#gridprocess").html(processing());
		var passdata = "&submittype=filter&searchtext=" + textfield + "&subselection=" + subselection + "&managedarea=" + managedarea + "&disablelogin=" + disablelogin +"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/reportmanagers.php";
		ajaxobjext95 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
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
						$("#gridprocess").html('');
						$("#tabgroupgridc1_1").html(response[1]);
						//document.getElementById("getmorelink").innerHTML = response[2];
						//document.getElementById("gridprocess").innerHTML = ' => Filter Applied (' + response[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]';
					}
					else
					{
						$("#gridprocess").html(scripterror1());
					}
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
	$("#gridprocess").html(processing());
	var form = $("#filterform");
	var textfield = $("#hiddensearchcriteria").val();
	var subselection = $("#hiddendatabasefield").val();
	if(type == 'managerlist')
	{
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'view')
	{
		var managedarea = form.managedarea.value; //alert(managedarea)
		var disablelogin = form.disablelogin.value;
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&searchtext="+textfield + "&subselection="+subselection + "&managedarea=" + managedarea + "&disablelogin=" + disablelogin +"&dummy=" + Math.floor(Math.random()*10230000000);
	}
	var queryString = "../ajax/reportmanagers.php";// alert(passdata)
	ajaxobjext96 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{	
				var ajaxresponse = response.split('|^|'); //alert(ajaxresponse)
				if(ajaxresponse[0] == '1')
				{
					$('#resultgrid').html($('#tabgroupgridc1_1').html()) ;
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelink').html(ajaxresponse[2]);
					$("#gridprocess").html(' => [Total number of Managers (' + ajaxresponse[3] +' Records)]');
				}
				else if(ajaxresponse[0] == '2')
				{
					$("#gridprocess").html('') ;
					$('#tabgroupgridc1_1').html(errormessage(ajaxresponse[1])); 
				}
				else
				{
					$("#gridprocess").html(scripterror1());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});			
}

