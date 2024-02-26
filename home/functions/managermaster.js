// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing()) ;
	var passdata = "&submittype=griddata&dummy="+Math.floor(Math.random()*1000782200000);
	var querystring = "../ajax/managermaster.php";
	ajaxobjext10 = $.ajax(
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
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]) ;
					$("#totalcount").html("Total count : " + ajaxresponse[3]) ;
					$("#gridprocess").html('');
				}
				else
				{
					$("#tabgroupgridc1_1").html(errormessage("No more records found to be displayed")) ;
				}
			}
		}, 
		error: function(a,b)
		{
			$("msg_box").html(scripterror());
		}
	});		
}


function formsubmit(command)
{
	var form = $("#managerform");
	var msg_box = $("#msg_box");

	var field = $("#form_name");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter a Name.")) ; field.focus(); return false;}
	var field = $("#form_location");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Location.")) ; field.focus(); return false;}
	var field = $("#form_email");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the email ID.")) ; field.focus(); return false;}
	if(checkemail(field.val()) == false)
	{ msg_box.html(errormessage("Please Enter Valid email ID.")) ; field.focus(); return false;}
	var field = $("#form_cell");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter Cell Number.")) ; field.focus(); return false;}
	var field = $("#form_cell");  
	if (!validatecell(field.val()))
	{ msg_box.html(errormessage("Please Enter Valid Cell Number.")) ; field.focus(); return false;}
	var field = $("#form_username");  
	if (!field.val())
	{ msg_box.html( errormessage("Please Enter the username.")) ; field.focus(); return false;}
	var field = $("#form_password");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the password.")); field.focus(); return false;}
	var field =  $("#form_branch");
	if(!field.val())
	{ msg_box.html(errormessage("Please Select the Branch.")) ; field.focus(); return false;} //alert(field.value);
	
	var field =  $("#form_managedarea");
	if(!field.val())
	{ msg_box.html(errormessage("Please Select the Area.")) ; field.focus(); return false;} //alert(field.value);

	if( $("#form_disablelogin:checked").val() == 'on') disablelogin = 'yes'; else disablelogin = 'no';
	if($("#transferuploadedleads:checked").val() == 'on')
		var transferuploadedleads = "1";
	else
		var transferuploadedleads = "0";
	if($("#branchhead:checked").val() == 'on')
		var branchhead = "yes";
	else
		var branchhead = "no";
	if($("#showmcacompanies:checked").val() == 'on')
		var showmcacompanies = "yes";
	else
		var showmcacompanies = "no";
		

	msg_box.html(processing());
	if(command == 'save')
	{
		var passdata = "&submittype=save&form_recid=" + $("#form_recid").val() + "&form_name=" + $("#form_name").val() + "&form_location=" +  $("#form_location").val() + "&form_email=" +  $("#form_email").val() + "&form_cell=" +  $("#form_cell").val() + "&form_username=" +  $("#form_username").val() + "&form_password=" +  $("#form_password").val() + "&transferuploadedleads=" + transferuploadedleads+"&form_disablelogin="+disablelogin+"&managedarea="+ $("#form_managedarea").val() +"&branch="+ $("#form_branch").val()+"&branchhead="+ branchhead+"&showmcacompanies="+ showmcacompanies+"&dummy="+Math.floor(Math.random()*1000782200000);
		var querystring = "../ajax/managermaster.php";
	}
	else if(command == 'delete')
	{
		var passdata = "&submittype=delete&form_recid=" +  $("#form_recid").val() +"&dummy="+Math.floor(Math.random()*1000782200000);
		var querystring = "../ajax/managermaster.php";
	}
	//alert(passdata)
	ajaxobjext11 = $.ajax(
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
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1])) ;
					griddata('');
					newentry();
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1]));
					griddata('');
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
	$("#managerform")[0].reset();
	enablesave();
	disabledelete();
}



function gridtoform(id)
{
	var form = $("#managerform");
	$("#gridprocess").html( processing());
	var passdata = "&submittype=gridtoform&form_recid=" + id+"&dummy="+Math.floor(Math.random()*1000782200000);
	var querystring = "../ajax/managermaster.php";
	ajaxobjext12 = $.ajax(
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
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('') ;
					$('#form_recid').val(ajaxresponse[1]);
					$('#form_name').val(ajaxresponse[2]);
					$('#form_location').val(ajaxresponse[3]);
					$('#form_email').val(ajaxresponse[4]) ;
					$('#form_cell').val(ajaxresponse[5]);
					$('#form_username').val(ajaxresponse[6]);
					$('#form_password').val(ajaxresponse[7]);
					$('#hiddenpwd').html(ajaxresponse[7]) ;
					if(ajaxresponse[8] == "1")
						$("#transferuploadedleads").attr('checked',true);
					else
						$("#transferuploadedleads").attr('checked',false);
					autochecknew($("#form_disablelogin"),ajaxresponse[9]); 
					autoselect('form_managedarea',ajaxresponse[10]);
					autoselect('form_branch',ajaxresponse[11]);
					autochecknew($("#branchhead"),ajaxresponse[12]); 
					autochecknew($("#showmcacompanies"),ajaxresponse[13]); 
					$('#msg_box').html('');
					enabledelete();
					enablesave();
				}
			}
		}, 
		error: function(a,b)
		{
			$("msg_box").html(scripterror());
		}
	});		
}


function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var querystring = "../ajax/managermaster.php";
	ajaxobjext13 = $.ajax(
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
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('') ;
					$('#resultgrid').html($('#tabgroupgridc1_1').html()) ;
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
					$('#getmorelink').html(ajaxresponse[2]) ;
					$('#totalcount').html("Total Count :  " + ajaxresponse[3]);
					
				}
				else
				{
					$("#gridprocess").html('');
					$("#getmorelink").html(errormessage("No more records found to be displayed"));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#msg_box").html(scripterror()) ;
		}
	});	
}