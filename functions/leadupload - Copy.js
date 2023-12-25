// JavaScript Document

function districtselect(selectid,comparevalue)
{
	var code = $('#form_state').val();
	var outputselect = $('#districtdiv');
	var passdata = "&submittype=form_state&code=" + code +"dummy=" + Math.floor(Math.random()*100032680100) ;
	var queryString = "../ajax/leadupload.php";
	ajaxobjext36 = $.ajax(
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
			outputselect.html(scripterror());
		}
	});		
	$('#regiondiv').html('<select name="form_region" id="form_region"><option value = "">- - - -Select a District First - - - -</option></select>') ;
}

function regionselect(bypasscode,selectid,comparevalue)
{
	
	var code;
	if(bypasscode)
		code = bypasscode;
	else
		code = $('#form_district').val();
	var outputselect = $('#regiondiv');
	var passdata = "&submittype=form_district&code=" + code+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/leadupload.php";
	ajaxobjext37 = $.ajax(
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
			outputselect.html(scripterror());
		}
	});		
}

function formsubmit(command)
{
	var form = $("#leaduploadform");
	var msg_box = $("#msg_box");

	var field = $("#form_companyname");  
	if (!field.val())
	{ msg_box.html( errormessage("Please Enter the Company Name.")); field.focus(); return false;}
	var field = $("#form_name");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Name.")); field.focus(); return false;}
	var field = $("#form_address");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Address.")); field.focus(); return false;}
	var field = $("#form_state");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a State.")); field.focus(); return false;}
	var field = $("#form_district");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a District.")); field.focus(); return false;}
	var field = $("#form_region");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a Region.")); field.focus(); return false;}
	var field = $("#form_place");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Place name.")); field.focus(); return false;}
	var field = $("#form_stdcode");  
	if(field.val()) { if(!validatestdcode(field.val())) { msg_box.html(errormessage('Please Enter a valid STD Code.')); field.focus(); return false; } }
	var field = $("#form_phone");  
	/*if (!field.value)
	{ msg_box.innerHTML = "Please Enter the Phone Number."; field.focus(); return false;}*/
	if(field.val()) { if(!validatephone(field.val())) { msg_box.html(errormessage('Please Enter a valid Phone Number.')); field.focus(); return false; } }
	var field = $("#form_cell");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the cell Number.")); field.focus(); return false;}
	if(field.val()) { if(!validatecell(field.val())) { msg_box.html(errormessage('Please Enter the valid Cell Number.')) ; field.focus(); return false; } }
	var field = $("#form_email");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the email ID.")) ; field.focus(); return false;}
	if(checkemail(field.val()) == false)
	{ msg_box.html(errormessage("Please Enter the Valid email ID.")); field.focus(); return false;}
	var field = $("#form_product");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select the Product Name.")); field.focus(); return false;}
	var field = $("#form_source");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select the Source of Lead.")); field.focus(); return false;}
	if($("#aspermapping:checked").val() != 'on')
	{
		var field = $("#form_dealer");  
		if (!field.val())
		{ msg_box.html(errormessage("Please Select the Dealer name.")); field.focus(); return false;}
	}
	
	var dealervalue; 
	if($("#aspermapping:checked").val() == 'on')
	{
		dealervalue = "mapping";
	}
	else
	{
		dealervalue = $("#form_dealer").val();
	}
	msg_box.html(processing());
	if(command == 'save')
	{
		var passdata = "&submittype=save&form_companyname=" + encodeURIComponent($("#form_companyname").val()) + "&form_name=" + encodeURIComponent($("#form_name").val()) + "&form_address=" + encodeURIComponent($("#form_address").val()) + "&form_region=" + encodeURIComponent($("#form_region").val()) + "&form_place=" + encodeURIComponent($("#form_place").val()) + "&form_phone=" + encodeURIComponent($("#form_phone").val())+ "&form_cell=" + encodeURIComponent($("#form_cell").val())+ "&form_stdcode=" + encodeURIComponent($("#form_stdcode").val()) + "&form_email=" + encodeURIComponent($("#form_email").val()) + "&form_product=" + encodeURIComponent($("#form_product").val()) + "&form_source=" + encodeURIComponent($("#form_source").val()) + "&form_dealer=" + encodeURIComponent(dealervalue) + "&form_leadremarks=" + encodeURIComponent($("#form_leadremarks").val()) +"&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
		var queryString = "../ajax/leadupload.php";
	}
	ajaxobjext38 = $.ajax(
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
				var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1]));
					//griddata();
					newentry();
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1]));
					//griddata();
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
	$("#districtdiv").html('<select name="form_district" id="form_district"><option value="" selected="selected">- - - -Select a State First - - - -</option></select>');
	$("#regiondiv").html('<select name="form_region" class="formfields" id="form_region"><option value = "">- - - -Select a District First - - - -</option></select>');
	if($('#cookie_usertype').val() != 'Dealer')
	{
		$('#form_dealer').attr('disabled',true) ;
		$('#form_dealer').hide();
	}
	$("#leaduploadform")[0].reset();
}

function checkaspermapping()
{
	if($('#aspermapping:checked').val() == 'on')
	{
		$('#form_dealer').attr('disabled',true) ;
		$('#form_dealer').hide();
	}
	else
	{
		$('#form_dealer').attr('disabled',false) ;
		$('#form_dealer').show();
	}
}
