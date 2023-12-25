// JavaScript Document

function filtering()
{
	var form = $("#filterform");
	var msg_box = $("#msg_box");

	if(compare2dates(($("#DPC_fromdate").val()),($("#DPC_todate").val())) == false)
	{ msg_box.html("<span class='msgboxred'>From date cannot be greater than To date.</span>");  return false;}

	if($("#considerfollowup").is(':checked') == true)
	{
		if(compare2dates(($("#DPC_filter_followupdate1").val()),($("#DPC_filter_followupdate2").val())) == false)
		{ msg_box.html(errormessage("Followup From date cannot be greater than Followup To date.")); $("#DPC_filter_followupdate1").focus(); return false;}
		
		var filter_followupdate1 = $("#DPC_filter_followupdate1").val();
		$("#filter_followupdate1hdn").val($("#DPC_filter_followupdate1").val());
		var filter_followupdate2 = $("#DPC_filter_followupdate2").val();
		$("#filter_followupdate2hdn").val($("#DPC_filter_followupdate2").val());
	}
	else
	{
		var filter_followupdate1 = "dontconsider";
		$("#filter_followupdate1hdn").val("dontconsider");
		var filter_followupdate2 = "dontconsider";
		$("#filter_followupdate1hdn").val("dontconsider");
	}	
	$("#msg_box").html('');
	form.submit();
}


function filterfollowupdates()
{
	var considerfollowup = $("#considerfollowup");
	var filter_followupdate2 = $("#DPC_filter_followupdate2");
	var filter_followupdate1 = $("#DPC_filter_followupdate1");
	
	if(considerfollowup.is(":checked") == false)
	{
		filter_followupdate1.attr('disabled',true);
		filter_followupdate2.attr('disabled',true);
	}
	else if(considerfollowup.is(":checked") == true)
	{
		filter_followupdate1.attr('disabled',false);
		filter_followupdate2.attr('disabled',false);
	}
}


