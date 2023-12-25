// JavaScript Document

function filtering()
{
	var form = $("#filterform");
	var msg_box = $("#msg_box");

/*	var field = form.fromdate;
	if (!field.value)
	{ msg_box.innerHTML = errormessage("Please Enter 'From date'."); field.focus(); return false;}
	if(checkdate(field.value) == false)
	{ msg_box.innerHTML = errormessage("Enter a valid 'From date' [dd-mm-yyyy]."); field.focus(); return false;}
	var field = form.todate;
	if (!field.value)
	{ msg_box.innerHTML = errormessage("Please Enter 'To Date'."); field.focus(); return false;}
	if(checkdate(field.value) == false)
	{ msg_box.innerHTML = errormessage("Enter a valid 'To date' [dd-mm-yyyy]."); field.focus(); return false;}*/
	if(compare2dates(($("#DPC_fromdate").val()),($("#DPC_todate").val())) == false)
	{ msg_box.html( errormessage("From date cannot be greater than To date.")); $("#DPC_fromdate").focus(); return false;}
	
	form.submit();
	msg_box.html('');
}


