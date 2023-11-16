// JavaScript Document

function filtering() {
	var form = $("#filterform");
	var msg_box = $("#msg_box");

	if (compare2dates(($("#DPC_fromdate").val()), ($("#DPC_todate").val())) == false) { msg_box.html("<span class='msgboxred'>From date cannot be greater than To date.</span>"); $("#DPC_fromdate").focus(); return false; }
	msg_box.html('');
	form.submit();
}


