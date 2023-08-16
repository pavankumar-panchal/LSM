// JavaScript Document

function filtering(type)
{
	var form = $("#leadstatuschartform");
	var msg_box = $("#errormessage");

	if(compare2dates(($("#DPC_fromdate").val()),($("#DPC_todate").val())) == false)
	{ msg_box.html(errormessage("From date cannot be greater than To date.")); $("#DPC_fromdate").focus(); return false;}
	
	form.submit();
	
	
}