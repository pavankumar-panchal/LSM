// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var queryString = "../ajax/regionmaster.php";
	var passdata = "&switchtype=griddata&dummy="+Math.floor(Math.random()*1000782200000);
	//$.ajax.abort ();
	ajaxobject1 = $.ajax(
	{
		type: "POST", url: queryString, data: passdata, cache: false,
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
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]);
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess").html('');
				}
				else
				{
					$("#gridprocess").html('');
					$("#tabgroupgridc1_1").html(errormessage("No more records found to be displayed"));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#msg_box").html(scripterror());
		}
	});
	//ajaxobjext1.abort();
}

function formsubmit(command)
{
	var form = $('#regionform');
	var msg_box = $("#msg_box");

	var field = $("#form_state");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a State.")); field.focus(); return false;}
	var field = $("#form_district")
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a District.")) ; field.focus(); return false;}
	var field = $("#form_region");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter Region Name.")); field.focus(); return false;}
	var field = $("#form_managedarea");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a Managed Area.")) ; field.focus(); return false;}

	msg_box.html(processing());
	
	if(command == 'save')
	{
		var passdata = "&switchtype=save&form_recid=" + $("#form_recid").val()+"&form_state="+ $("#form_state").val() + "&form_district=" + $("#form_district").val() + "&form_region=" + $("#form_region").val() + "&form_managedarea=" + $("#form_managedarea").val() + "&form_fixed_added=" + $("#form_fixed_added").val() + "&dummy=" + Math.floor(Math.random()*1000782200000) ;
		var querystring = "../ajax/regionmaster.php";

	} 
	else if(command == 'delete')
	{
		var field = $("#form_fixed_added");  
		if (field.val() == 'fixed')
		{ 
			msg_box.html( errormessage("Region is Predefined. Cannot be deleted..")); 
			return false;
		}
		var passdata = "&switchtype=delete&form_recid=" + $("#form_recid").val() + "&dummy=" + Math.floor(Math.random()*1000782200000);
		var querystring = "../ajax/regionmaster.php";

	}
	ajaxobjext2 = $.ajax(
	{
		type: "POST", url: querystring, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var ajaxresponse = response.split('^');//alert(ajaxcal001.responseText);
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1]));
					griddata('');
					newentry();
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1])) ;
					griddata('');
				}
				else
				{
					msg_box.html(scripterror()) ;
				}
			}
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror()) ;
			ajaxobjext2.abort();
		}
	});
}

function newentry()
{
//	document.getElementById("msg_box").innerHTML = '';
	$("#form_recid").val('') ;
	$("#form_fixed_added").val('');
	$("#districtdiv").html('<select name="district" id="district"><option value="" selected="selected">- - - -Select a State First - - - -</option></select>') ;
	$("#regionform")[0].reset();
	enablesave();
	disabledelete();
}



function gridtoform(id)
{
	$("#gridprocess").html(processing());
	var queryString = "../ajax/regionmaster.php";
	var passdata = "&switchtype=gridtoform&form_recid="+id+"dummy="+ Math.floor(Math.random()*100032680100);
	ajaxobjext3 = $.ajax(
	{
		type: "POST", url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				$("#gridprocess").html('');
				var ajaxresponse = response.split("^");
				$('#form_recid').val(ajaxresponse[0]);
				autoselect('form_state',ajaxresponse[1]);
				districtselect('form_district',ajaxresponse[2]);
				$('#form_region').val(ajaxresponse[3]);
				autoselect('form_managedarea',ajaxresponse[4]);
				$('#form_fixed_added').val(ajaxresponse[5]);
				$('#msg_box').html('');
				enabledelete();
				enablesave();
			}
		}, 
		error: function(a,b)
		{
				$("#gridprocess").html('');
				$('#msg_box').html( scripterror());
		}
	});
	//ajaxobjext3.abort();
}

function districtselect(selectid,comparevalue)
{
	var state = $('#form_state').val();
	var districtdiv = $('#districtdiv');
	var passdata = "&switchtype=state&statecode="+state+"&dummy="+Math.floor(Math.random()*1000782200000); //alert(passdata);
	var querystring = "../ajax/regionmaster.php";//alert(querystring);
	ajaxobjext4 = $.ajax(
	{
		type: "POST", url: querystring, data: passdata, cache: false,
		success: function(response,status)
		{
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				districtdiv.html(response); //alert(ajaxcal002 .responseText);
					if(selectid && comparevalue)
						autoselect(selectid,comparevalue);
			}
		},
		error: function(a,b)
		{
			districtdiv.html(scripterror());
		}
	});	
	//ajaxobjext4.abort();
}

function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortregionajaxprocess()" class="abort">(STOP)</span>');
	var passdata = "&switchtype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var querystring = "../ajax/regionmaster.php";
	ajaxobjext5 = $.ajax(
		{
			type: "POST",url: querystring, data: passdata, cache: false,
			success: function(response,status)
			{
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var ajaxresponse = response.split('^');//alert(response);
					if(ajaxresponse[0] == '1')
					{
						$("#gridprocess").html('');
						$('#resultgrid').html($('#tabgroupgridc1_1').html());
						$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
						$('#getmorelink').html(ajaxresponse[2]);
						$('#totalcount').html("Total Count :  " + ajaxresponse[3]);
						
					}
					else
					{
						$("#gridprocess").html(scripterror());
					}
				}
			}, 
			error: function(a,b)
			{
				$("#gridprocess").html('');
				$("#msg_box").html(scripterror());
			}
		});
		//ajaxobjext5.abort();
}

function abortregionajaxprocess()
{
	ajaxobjext5.abort();
	$("#gridprocess").html('');
}