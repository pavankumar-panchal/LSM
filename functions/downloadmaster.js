// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/downloadmaster.php";
	ajaxobjext20 = $.ajax(
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
				var ajaxresponse = response.split('^');
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('');
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]);
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}

function formsubmit(command)
{
	var form = $("#downloadform");
	var msg_box = $("#msg_box");

	var field = $("#form_name");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Name.")); field.focus(); return false;}
	var field = $("#form_description");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Description for the item.")) ; field.focus(); return false;}
	var field = $("#form_fullurl");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Full URL from where it can be downloaded.")); field.focus(); return false;}
	var field = $("#form_categoryv");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a V-Category.")); field.focus(); return false;}
	var field = $("#form_categoryh");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a H-Category.")); field.focus(); return false;}

	msg_box.html(processing());
	
	if(command == 'save')
	{
		var passdata = "&submittype=save&form_recid=" + $("#form_recid").val() + "&form_name=" + encodeURIComponent($("#form_name").val()) + "&form_description=" + encodeURIComponent($("#form_description").val()) + "&form_fullurl=" + encodeURIComponent($("#form_fullurl").val()) + "&form_categoryv=" + encodeURIComponent($("#form_categoryv").val()) + "&form_categoryh=" + encodeURIComponent($("#form_categoryh").val())+"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/downloadmaster.php";
	}
	else if(command == 'delete')
	{
		var passdata = "&submittype=delete&form_recid=" + $("#form_recid").val() +"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/downloadmaster.php";
	}
	ajaxobjext21 = $.ajax(
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
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1]));
					griddata('');
					newentry();
				}
				else
				{
					msg_box.html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
		}
	});		

}

function newentry()
{
//	document.getElementById("msg_box").innerHTML = '';
	$("#form_recid").val('');
	$("#downloadform")[0].reset();
	enablesave();
	disabledelete();
}


function gridtoform(id)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=gridtoform&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/downloadmaster.php";
	ajaxobjext22 = $.ajax(
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
				var ajaxresponse = response.split("|^|");
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('');
					$('#form_recid').val(ajaxresponse[1]);
					$('#form_name').val(ajaxresponse[2]);
					$('#form_description').val(ajaxresponse[3]);
					$('#form_fullurl').val(ajaxresponse[4]);
					autoselect('form_categoryv',ajaxresponse[5]);
					autoselect('form_categoryh',ajaxresponse[6]);
					$('#msg_box').html('');
					enabledelete();
					enablesave();
				}
				else
				{
					$('#msg_box').html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$('#msg_box').html(scripterror());
			$("#gridprocess").html('');
		}
	});		
}


function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortdownloadajaxprocess()" class="abort">(STOP)</span>');
	var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var queryString = "../ajax/downloadmaster.php";
	ajaxobjext23 = $.ajax(
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
				var ajaxresponse = response.split('^');
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelink').html(ajaxresponse[2]);
					$('#totalcount').html("Total Count :  " + ajaxresponse[3]);
				}
				else
				{
					$('#tabgroupgridc1_1').html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$('#tabgroupgridc1_1').html(scripterror());
		}
	});			
}


function abortdownloadajaxprocess()
{
	ajaxobjext23.abort();
	$("#gridprocess").html('');
}