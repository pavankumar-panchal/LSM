// JavaScript Document

function filtering(type)
{
	var form = $("#demostatsform");
	var msg_box = $("#errormessage");

	if(compare2dates(($("#DPC_fromdate").val()),($("#DPC_todate").val())) == false)
	{ msg_box.html(errormessage("From date cannot be greater than To date.")); $("DPC_fromdate").focus(); return false;}
	
	if(compare2dates(($("#DPC_fromdatedemo").val()),($("#DPC_todatedemo").val())) == false)
	{ msg_box.html(errormessage("From Demo date cannot be greater than To Demo date.")); $("#DPC_fromdatedemo").focus(); return false;}
	
	msg_box.html('');
	form.submit();
	
	
}