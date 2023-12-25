// JavaScript Document

function validate()
{
	var form = $("#profileform");
	var msg_box = $("#msg_box");

	var field = $("#form_name");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Name.")); $("#form_name").focus(); return false;}
	var field = $("#form_address");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Address.")) ; $("#form_address").focus(); return false;}
	var field = $("#form_phone");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Phone Number.")); $("#form_phone").focus(); return false;}
	if(validatephone(field.val()) == false)
	{msg_box.html(errormessage("Please Enter Valid Phone Number."));$('#form_phone').focus();return false;}
	var field = $("#form_mobile");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Cell Number.")); $("#form_mobile").focus(); return false;}
	var field = $("#form_mobile");  
	if (!validatecell(field.val()))
	{ msg_box.html(errormessage("Please Enter Valid Cell Number.")); $("#form_mobile").focus(); return false;}
	//alert($("#form_name").val()); alert($("#form_address").val()); alert($("#form_phone").val()); alert($("#form_mobile").val());
	var passdata = "&submittype=save&form_name=" +encodeURIComponent($("#form_name").val()) + "&form_address=" + encodeURIComponent($("#form_address").val()) + "&form_phone=" + encodeURIComponent($("#form_phone").val())+"&form_cell="+encodeURIComponent($("#form_mobile").val())+"&form_website="+$("#form_website").val()+"&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
	msg_box.html(processing());
	var queryString = "../ajax/dealerprofile.php";
	ajaxobjext106 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			msg_box.html('');
			var ajaxresponse = response.split('^');
			if(ajaxresponse[0] == '1')
				msg_box.html(successmessage(ajaxresponse[1]));
			else if(ajaxresponse[0] == '2')
				msg_box.html(errormessage(ajaxresponse[1]));
			else
				msg_box.html(scripterror());
			
		}, 
		error: function(a,b)
		{
			msg_box.html('');
			msg_box.html(scripterror());
		}
	});		
}
