// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reportleads.php";
	ajaxobjext103 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response1 = response.split("|^|");
			if(response1[0] == '1')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html(' => [Leads of Last 2 Days (' + response1[3] +' Records)]');
			}
			else if(response1[0] == '2')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html(' => [Leads of Last 2 Days (' + response1[2] +' Records)]');
			}
			else
			{
				$("#gridprocess").html(scripterror1());
			}			
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});
}

function filtering(command)
{
	var form = $("#filterform");
	var msg_box = $("#msg_box2");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var datatype =  $("input[name='datatype']:checked").val();
	var selectedvalue = $('#productid'); 
	$('#hiddenproductid').val($('#productid').val());
	var followuppending = $('#followuppending');
	var followupmade = $('#followupmade');
	var remarks = $('#remarks');
	// selected optgroup label 
	var grouplabel = '';
	if($('#productid').val() != '')
	{
		grouplabel= $('#productid :selected').parents('OPTGROUP').attr('label');
		$('#hiddengrouplabel').val(grouplabel);
	}
	
	var field = $("#DPC_fromdate");
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter 'From date'.")); field.focus(); return false;}
	if(checkdate(field.val()) == false)
	{ msg_box.html(errormessage("Enter a valid 'From date' [dd-mm-yyyy].")); field.focus(); return false;}
	var field =  $("#DPC_todate");
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter 'To Date'.")); field.focus(); return false;}
	if(checkdate(field.val()) == false)
	{ msg_box.html(errormessage("Enter a valid 'To date' [dd-mm-yyyy].")); field.focus(); return false;}
	if(compare2dates(($("#DPC_fromdate").val()),($("#DPC_todate").val())) == false)
	{ msg_box.html( errormessage("From date cannot be greater than To date.")); form.fromdate.focus(); return false;}

	if($("#considerfollowup:checked").val() == 'on')
	{
		var field = $("#DPC_filter_followupdate1");
		if (!field.val())
		{ msg_box.html(errormessage("Please Enter 'Followup From date'.")); field.focus(); return false;}
		if(checkdate(field.val()) == false)
		{ msg_box.html(errormessage("Enter a valid 'Followup From date' [dd-mm-yyyy].")) ; field.focus(); return false;}
		var field = $("#DPC_filter_followupdate2");
		if (!field.val())
		{ msg_box.html(errormessage("Please Enter 'Followup To Date'.")); field.focus(); return false;}
		if(checkdate(field.val()) == false)
		{ msg_box.html(errormessage("Enter a valid 'Followup To date' [dd-mm-yyyy].")) ; field.focus(); return false;}
		if(compare2dates(($("#DPC_filter_followupdate1").val()),($("#DPC_filter_followupdate2").val())) == false)
		{ msg_box.html(errormessage("Followup From date cannot be greater than Followup To date.")); form.filter_followupdate1.focus(); return false;}
		
		var filter_followupdate1 = $("#DPC_filter_followupdate1").val();
		$("#filter_followupdate1hdn").val($("#DPC_filter_followupdate1").val());
		var filter_followupdate2 = $("#DPC_filter_followupdate2").val();
		$("#filter_followupdate2hdn").val($("#DPC_filter_followupdate2").val());
		
		if($("#followuppending").is(":checked") == true)
		{
			var followupcheck = 'followuppending';
		}	
		else if($("#followupmade").is(":checked") == true)
		{
			var followupcheck = 'followupmade';
		}
	}
	else
	{
		var filter_followupdate1 = "dontconsider";
		$("#filter_followupdate1hdn").val("dontconsider") ;
		var filter_followupdate2 = "dontconsider";
		$("#filter_followupdate2hdn").val("dontconsider");
		var followupcheck = '';
	}
	if($("#dropterminatedstatus:checked").val() == 'true')
	{
		var dropterminatedstatus = 'true';
	}
	else
	{
		var dropterminatedstatus = 'false';
	}

	if(command == 'excel')
		form.submit();
	else
	{
		$('#hiddenfromdate').val($('#DPC_fromdate').val());
		$('#hiddentodate').val($('#DPC_todate').val());
		$('#hiddendealerid').val($('#dealerid').val());
		$('#hiddengivenby').val($('#givenby').val());
		$('#hiddenleadstatus').val($('#leadstatus').val());
		$('#srchhiddenfield').val(textfield);      //alert(textfield);
		$('#subselhiddenfield').val(subselection); //alert(subselection);
		$('#datatypehiddenfield').val(datatype); //alert(datatype);
		$('#followedbyhidden').val($("#followedby").val()); // alert(form.followedby.value)
		$('#hiddensource').val($("#form_source").val()); //alert(form.form_source.value);
		
		$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortfilterreportajaxprocess(\'initial\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&fromdate=" + encodeURIComponent($("#DPC_fromdate").val()) + "&todate=" + encodeURIComponent($("#DPC_todate").val()) + "&dealerid=" + encodeURIComponent($("#dealerid").val()) + "&givenby=" + encodeURIComponent($("#givenby").val()) + "&productid=" + encodeURIComponent($("#productid").val()) + "&leadstatus=" + encodeURIComponent($("#leadstatus").val()) + "&filter_followupdate1=" + encodeURIComponent(filter_followupdate1) + "&filter_followupdate2=" + encodeURIComponent(filter_followupdate2) + "&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus)+"&searchtext="+ encodeURIComponent($("#srchhiddenfield").val())+"&subselection="+encodeURIComponent($("#subselhiddenfield").val())+"&datatype="+encodeURIComponent($("#datatypehiddenfield").val())+"&followedby="+encodeURIComponent($("#followedbyhidden").val())+"&leadsource="+encodeURIComponent($("#hiddensource").val())+"&grouplabel="+grouplabel+"&followupcheck="+followupcheck+"&remarks="+$("#remarks").val()+"&dummy=" + Math.floor(Math.random()*10230000000) ; //alert(passdata);
		var queryString = "../ajax/reportleads.php";
		ajaxobjext104 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var response2 = response.split("|^|");
				if(response2[0] == '1')
				{
					$("#tabgroupgridc1_1").html(response2[1]);
					$("#getmorelink").html(response2[2]);
					$("#gridprocess").html(' => Filter Applied (' + response2[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]');
					msg_box.html('');
				}
				else if(response2[0] == '2')
				{
					$("#msg_box2").html(errormessage(response2[1]));
					$("#gridprocess").html('');
				}
				else if(response2[0] == '3')
				{
					$("#tabgroupgridc1_1").html(errormessage(response2[1]));
					$("#gridprocess").html('');
				}
				else
				{
					$("#gridprocess").html(scripterror1());
				}	
			}, 
			error: function(a,b)
			{
				$("#gridprocess").html(scripterror1());
			}
		});
	}
}

function filterfollowupdates()
{
	var considerfollowup = $("#considerfollowup");
	var filter_followupdate2 = $("#DPC_filter_followupdate2");
	var filter_followupdate1 = $("#DPC_filter_followupdate1");
	var followedby = $("#followedby");
	var remarks = $("#remarks");
	var followupmade = $("#followupmade");
	var followuppending = $("#followuppending");
	if($("#considerfollowup:checked").val() != 'on')
	{
		filter_followupdate1.attr('disabled',true);
		filter_followupdate2.attr('disabled',true);
		followedby.attr('disabled',true);
		remarks.attr('disabled',true);
		followupmade.attr('disabled',true);
		followuppending.attr('disabled',true);
	}
	else if($("#considerfollowup:checked").val() == 'on')
	{
		filter_followupdate1.attr('disabled',false);
		filter_followupdate2.attr('disabled',false);
		followedby.attr('disabled',false);
		remarks.attr('disabled',false);
		followupmade.attr('disabled',false);
		followuppending.attr('disabled',false);
	}
}

function getmorerecords(startlimit,slnocount,showtype,type)
{
	var form = $("#filterform");
	var followuppending = $('#followuppending');
	var followupmade = $('#followupmade');
	var remarks = $('#remarks');
	if($("#dropterminatedstatus:checked").val() == 'true')
	{
		var dropterminatedstatus = 'true';
	}
	else
	{
		var dropterminatedstatus = 'false';
	}
	if($("#followuppending").is(":checked") == true)
	{
		var followupcheck = 'followuppending';
	}	
	else if($("#followupmade").is(":checked") == true)
	{
		var followupcheck = 'followupmade';
	}
	if(type == 'reportlead')
	{
		$("#gridprocess").html(processing()) ;
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'view')
	{
		$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortfilterreportajaxprocess(\'showmore\')" class="abort">(STOP)</span>') ;
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&fromdate=" + encodeURIComponent($("#hiddenfromdate").val()) + "&todate=" + encodeURIComponent($("#hiddentodate").val()) + "&dealerid=" + encodeURIComponent($("#hiddendealerid").val()) + "&givenby=" + encodeURIComponent($("#hiddengivenby").val()) + "&productid=" + encodeURIComponent($("#hiddenproductid").val()) + "&leadstatus=" + encodeURIComponent($("#hiddenleadstatus").val()) + "&filter_followupdate1=" + encodeURIComponent($("#filter_followupdate1hdn").val()) + "&filter_followupdate2=" + encodeURIComponent($("#filter_followupdate2hdn").val()) + "&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus)+"&searchtext="+ encodeURIComponent($("#srchhiddenfield").val())+"&subselection="+encodeURIComponent($("#subselhiddenfield").val())+"&datatype="+encodeURIComponent($("#datatypehiddenfield").val())+"&followedby="+encodeURIComponent($("#followedbyhidden").val())+"&leadsource="+encodeURIComponent($("#hiddensource").val())+"&grouplabel="+encodeURIComponent($("#hiddengrouplabel").val())+"&followupcheck="+followupcheck+"&remarks="+$("#remarks").val()+"&dummy=" + Math.floor(Math.random()*10230000000);
	}
	var queryString = "../ajax/reportleads.php"; //alert(passdata);
	ajaxobjext105 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$("#gridprocess").html('');
				$('#resultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
				$('#getmorelink').html(ajaxresponse[2]);
				$("#gridprocess").html(' =>  (' + ajaxresponse[3] +' Records)');
				
			}
			else
			{
				$("#gridprocess").html(scripterror1());
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});	
}

function abortfilterreportajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext104.abort();	
		$("#gridprocess").html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext105.abort();
		$("#gridprocess").html('');
	}
	
}