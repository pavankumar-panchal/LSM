// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/dlrmbrmaster.php";
	ajaxobjext51 = $.ajax(
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
				var ajaxresponse1 = response.split('^'); //alert(ajaxresponse1);
				if(ajaxresponse1[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse1[1]);
					$("#getmorelink").html(ajaxresponse1[2]);
					$("#totalcount").html("Total count : " + ajaxresponse1[3]);
					$("#gridprocess").html('');
				}
				else if(ajaxresponse1[0] == '2')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse1[1]);
					$("#gridprocess").html('');
				}
				else
				{
					$("#resultgrid").html(errormessage("No more records found to be displayed."));
					$("#gridprocess").html('');
				}
			}
		}, 
		error: function(a,b)
		{
			$("#msg_box").html(scripterror());
			$("#gridprocess").html('');
		}
	});		
}

function formsubmit(command)
{
	var form = $("#dlrmbrform");
	var msg_box = $("#msg_box");

	var field = $("#form_name");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Name.")); field.focus(); return false;}
	var field = $("#form_email");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the email ID.")); field.focus(); return false;}
	if(checkemail(field.val()) == false)
	{ msg_box.html(errormessage("Please Enter the Valid email ID.")); field.focus(); return false;}
	var field = $("#form_cell");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Cell Number.")); field.focus(); return false;}
	var field = $("#form_username");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the username.")); field.focus(); return false;}
	var field = $("#form_password");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the password.")); field.focus(); return false;}
	var field = $("#form_disablelogin");
	if(field.is(":checked") == true) disablelogin = 'yes'; else disablelogin = 'no'; 
	msg_box.html(processing());
	
	if(command == 'save')
	{
		var passdata = "&submittype=save&form_recid="+ $("#form_recid").val() + "&form_name="+ encodeURIComponent($("#form_name").val()) + "&form_remarks="+ encodeURIComponent($("#form_remarks").val()) + "&form_cell="+ encodeURIComponent($("#form_cell").val()) + "&form_email="+ encodeURIComponent($("#form_email").val()) + "&form_username="+ encodeURIComponent($("#form_username").val()) + "&form_password="+ encodeURIComponent($("#form_password").val())+"&form_disablelogin="+disablelogin+"&dummy="+ Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/dlrmbrmaster.php";

	}
	else if(command == 'delete')
	{
		var passdata = "&submittype=delete&form_recid="+$("#form_recid").val()+"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/dlrmbrmaster.php";
	}
	ajaxobjext52 = $.ajax(
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
				var ajaxresponse2 = response.split('^'); //alert(ajaxresponse2)
				if(ajaxresponse2[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse2[1]));
					griddata('');
					newentry();
				}
				else if(ajaxresponse2[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse2[1]));
					griddata('');
					newentry();
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
//	$("#msg_box").innerHTML = '';
	$("#form_recid").val('') ;
	$("#dlrmbrform")[0].reset();
	enablesave();
	disabledelete();
}


function gridtoform(id)
{
	var form = $("#dlrmbrform");
	$("#gridprocess").html( processing());
	var passdata = "&submittype=gridtoform&form_recid="+ id+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/dlrmbrmaster.php";
	ajaxobjext53 = $.ajax(
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
				$("#gridprocess").html('');
				var response3 = response.split("|^|"); //alert(response3);alert(response3[8]);
				if(response3[0] == '1')
				{
					$('#form_recid').val(response3[1]);
					$('#form_name').val(response3[2]);
					$('#form_remarks').val(response3[3]);
					$('#form_email').val(response3[4]);
					$('#form_cell').val(response3[5]);
					$('#form_username').val(response3[6]);
					$('#form_password').val(response3[7]);
					autochecknew($('#form_disablelogin'),response3[8]);
					$('#msg_box').html('');
					enabledelete();
					enablesave();
				}
				else if(response3[0] == '2')
				{
					$('#msg_box').html(errormessage(response3[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#msg_box").html(scripterror());
			$("#gridprocess").html('');
		}
	});
}

function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var queryString = "../ajax/dlrmbrmaster.php";
	ajaxobjext54 = $.ajax(
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
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1] );
					$('#getmorelink').html( ajaxresponse[2]);
					$('#totalcount').html("Total Count :  " + ajaxresponse[3]);
					$("#gridprocess").html('');
					
				}
				else
				{
					$("#getmorelink").html(errormessage("No datas found to be displayed."));
					$("#gridprocess").html('');
				}
			}
		}, 
		error: function(a,b)
		{
			$("#msg_box").html(scripterror());
			$("#gridprocess").html('');
		}
	});
}