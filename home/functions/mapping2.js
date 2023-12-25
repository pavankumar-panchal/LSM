// JavaScript Document

function districtselect(selectid,comparevalue)
{
	var code = $('#form_state').val();
	var outputselect =  $('#districtdiv');
	var passdata = "&submittype=form_state&code=" + code +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mapping2.php";
	ajaxobjext28 = $.ajax(
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
				outputselect.html(response);
					if(selectid && comparevalue)
						autoselect(selectid,comparevalue);
			}
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror1());
		}
	});		
	$('#regiondiv').html('<select name="form_region" id="form_region"><option value = "">- - - -Select a District First - - - -</option></select>');
}

function regionselect(bypasscode,selectid,comparevalue)
{
	
	var code;
	if(bypasscode)
		code = bypasscode;
	else
		code = $('#form_district').val() ;
	var outputselect = $('#regiondiv');
	var passdata = "&submittype=form_district&code=" + code +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mapping2.php";
	ajaxobjext29 = $.ajax(
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
				outputselect.html(response);
					if(selectid && comparevalue)
						autoselect(selectid,comparevalue);
			}
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror1());
		}
	});		
}

function formsubmit(command)
{
	var form = $("#mappingform");
	var msg_box = $("#msg_box");

	var field = $("#form_state");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a State.")); field.focus(); return false;}
	var field = $("#form_district");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a District.")); field.focus(); return false;}
	var field = $("#form_region");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a Region.")); field.focus(); return false;}
	var field = $("#form_prdcategory");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a Category.")) ; field.focus(); return false;}

	msg_box.html(processing());
	
	if(command == 'save')
	{
		var passdata = "&submittype=save&form_recid=" + $("#form_recid").val() + "&form_region=" + $("#form_region").val() + "&form_prdcategory=" + $("#form_prdcategory").val() + "&form_dealerid=" + $("#form_dealerid").val() +"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/mapping2.php";
	}
	else if(command == 'delete')
	{
		var passdata = "&submittype=delete&form_recid=" + $("#form_recid").val() +"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/mapping2.php";
	}
	ajaxobjext30 = $.ajax(
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
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1]));
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
	$("#form_recid").val('');
	$("#districtdiv").html('<select name="form_district" id="form_district"><option value="" selected="selected">- - - -Select a State First - - - -</option></select>') ;
	$('#regiondiv').html('<select name="form_region" id="form_region"><option value = "">- - - -Select a District First - - - -</option></select>') ;
	$("#mappingform")[0].reset();
	enablesave();
	disabledelete();
}


function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mapping2.php";
	ajaxobjext31 = $.ajax(
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
				var ajaxresponse = response.split('^'); //alert(ajaxresponse);
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]) ;
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess").html('');
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

function gridtoform(id)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=gridtoform&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mapping2.php";
	ajaxobjext32 = $.ajax(
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
				var ajaxresponse = response.split("^");
				$("#gridprocess").html('');
				if(response[0] == '1')
				{
					$('#form_recid').val(ajaxresponse[1]);
					autoselect('form_state',ajaxresponse[2]);
					districtselect('form_district',ajaxresponse[3]);
					regionselect(ajaxresponse[3],'form_region',ajaxresponse[4]);
					autoselect('form_prdcategory',ajaxresponse[5]);
					$('#msg_box').html('');
					enabledelete();
					enablesave();
				}
				else
				{
					$('msg_box').html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$('msg_box').html(scripterror());
		}
	});	
}

function getmorerecords(startlimit,slnocount,showtype)
{
	document.getElementById("gridprocess").innerHTML = processing();
	var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var queryString = "../ajax/mapping2.php";
	ajaxobjext33 = $.ajax(
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
				var ajaxresponse = response.split('^');//alert(ajaxresponse);
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1] ) ;
					$('#getmorelink').html(ajaxresponse[2]);
					$('#totalcount').html("Total Count :  " + ajaxresponse[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html(errormessage("No datas found to be displayed."));
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