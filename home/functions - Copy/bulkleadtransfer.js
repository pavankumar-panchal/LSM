// JavaScript Document

var totaltransfered;
var totallooprun ;
var currentlooprun;
var totalleadcount;

function filtering(command)
{
	var form = $("#filterform");
	var msg_box = $("#msg_box2");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var datatype =  $("input[name='datatype']:checked").val();
	$('#selectall').attr('checked',false) ;
	$('#selectedleadscount').html('0.');
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
	{ msg_box.hmtl(errormessage("From date cannot be greater than To date.")); form.fromdate.focus(); return false;}

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

	if(command == 'resetform')
	{
		form[0].reset();
		$('#messagebox').html('');
		filterfollowupdates();
	}
	else
	{
		$('#tabgroupgridc1_1').html(processing()+'  '+ '<span onclick = "abortfilterajaxprocess()" class="abort">(STOP)</span>');
		$("#hiddenfromdate").val($("#fromdate").val());
		$("#hiddentodate").val($("#todate").val());
		var selectedvalue = $('#productid'); //alert(sel.value)
		$("#hiddenproductid").val($("#productid").val());
		// selected optgroup label 
		var grouplabel = '';
		if($('#productid').val() != '')
		{
			grouplabel= $('#productid :selected').parents('OPTGROUP').attr('label');
			$('#hiddengrouplabel').val(grouplabel);
		}
		
		$("#hiddendealerid").val($("#dealerid").val());
		$("#hiddengivenby").val($("#givenby").val());
		$("#hiddenleadstatus").val($("#leadstatus").val());
		$("#srchhiddenfield").val(textfield);      //alert(textfield);
		$("#subselhiddenfield").val(subselection); //alert(subselection);
		$("#datatypehiddenfield").val(datatype); //alert(datatype);
		$("#followedbyhidden").val($("#followedby").val()); // alert(form.followedby.value)
		$("#hiddensource").val($("#form_source").val()) ; //alert(form.form_source.value);
		$('#totalleads').html('');
		
		$("#messagebox").html(''); 
		
		
		// Remove the progress bar if it's there.
		//$("#progressbar").hide();
		//$("#abort").hide();
		var passdata = "&submittype=filter&fromdate=" + encodeURIComponent($("#DPC_fromdate").val()) + "&todate=" + encodeURIComponent($("#DPC_todate").val()) + "&dealerid=" + encodeURIComponent($("#dealerid").val()) + "&givenby=" + encodeURIComponent($("#givenby").val()) + "&productid=" + encodeURIComponent($("#productid").val()) + "&leadstatus=" + encodeURIComponent($("#leadstatus").val()) + "&filter_followupdate1=" + encodeURIComponent(filter_followupdate1) + "&filter_followupdate2=" + encodeURIComponent(filter_followupdate2) + "&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus)+"&searchtext="+ encodeURIComponent($("#srchhiddenfield").val())+"&subselection="+encodeURIComponent($("#subselhiddenfield").val())+"&datatype="+encodeURIComponent($("#datatypehiddenfield").val())+"&followedby="+encodeURIComponent($("#followedbyhidden").val())+"&leadsource="+encodeURIComponent($("#hiddensource").val())+"&grouplabel="+grouplabel+"&followupcheck="+followupcheck+"&remarks="+$("#remarks").val()+"&dummy=" + Math.floor(Math.random()*10230000000) ;
		var queryString = "../ajax/bulkleadtransfer.php";
		ajaxobjext39 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var response2 = response.split("|^|");//alert(response2[2]);
				if(response2[0] == '1')
				{
					$("#tabgroupgridc1_1").html(response2[1]);
					$("#leadcount").val(response2[2]) ;
					$("#totalleads").html('Total : ' + response2[2] +' Records');
					msg_box.innerHTML = "";
				}
				else if(response2[0] == '2')
				{
					$("#msg_box2").html(errormessage(response2[1]));
					$("#tabgroupgridc1_1").html('');
				}
				else if(response2[0] == '3')
				{
					$("#tabgroupgridc1_1").html(errormessage(response2[1]));
				}
				else
				{
					$("#tabgroupgridc1_1").html(scripterror1());
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror1());
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


// Function to select and deselect leads


function selectanddeselect(selecttype)
{
	var totalcheckedcount = 0;
	var leadcount = $('#leadcount').val(); 
	if(selecttype == 'all' && $('#selectall').is(':checked') == true)
	{
		for(var i = 1;i <= leadcount; i++)
		{ 
			totalcheckedcount++;
			var checkbox = 'transfercheckbox' + i;
			$('#' + checkbox).attr('checked',true) ;
		}
		$('#selectedleadscount').html(totalcheckedcount + ' .');
	}
	else if(selecttype == 'all' && $('#selectall').is(':checked') == false)
	{
		for(var i = 1;i <= leadcount; i++)
		{
			var checkbox = 'transfercheckbox' + i;
			$('#' + checkbox).attr('checked',false) ;
		}
		$('#selectedleadscount').html(totalcheckedcount + ' .');
	}
	else if(selecttype == 'countselected')
	{
		for(var i = 1;i <= leadcount; i++)
		{
			var checkbox = 'transfercheckbox' + i;
			if($('#'+checkbox).is(':checked') == true)
			totalcheckedcount++;
		}
		$('#selectedleadscount').html(totalcheckedcount + ' .');
	}
}



// Function to transfer leads.

function transferleads()
{
	var checkedcount = 0;
	var messagebox = $('#messagebox');
	var leadids =  $('#hiddenid');
	var todealer =  $('#todealer');
	var leadcount = $('#leadcount').val();
	totaltransfered = 0;
	totallooprun = 0;
	currentlooprun = 0;
	totalleadcount = 0;
	var leadidarray = new Array();
	for(var i = 1;i <= leadcount; i++)
	{ 
		var checkbox = 'transfercheckbox' + i;
		if($('#'+checkbox).is(':checked') == true)
		{
			checkedcount++;
			if(leadidarray == '')
			{
				leadidarray = leadidarray + $('#'+checkbox).val();
			
			}
			else
			{
				leadidarray = leadidarray + ',' + $('#'+checkbox).val();
			}
		}
	}
	if(leadcount == '0')
	{
		messagebox.html(errormessage('There are no Leads to be Transfered.'));return false;
	}
	else if(checkedcount == '0')
	{
		messagebox.html(errormessage('Please Select Leads to Transfer .'));return false; 
	}
	
	else if(todealer.val() == '')
	{
		messagebox.html(errormessage('Please Select Dealer to Transfer Leads.'));return false;
	}
	else
	{
		var selected_text = $('#todealer option:selected').text(); 
		var text = "Are you sure you want to Transfer the Selected Leads to " +selected_text +" ??";
		var confirmation = confirm(text);
		if(confirmation)
		{
			leadids.val(leadidarray);
			messagebox.html('');
			var passdata = "&submittype=getloopingcount&checkedcount="+checkedcount+"&dummy=" + Math.floor(Math.random()*100032680100);
			var queryString = "../ajax/bulkleadtransfer.php";
			ajaxobjext40 = $.ajax(
			{
				type: "POST",url: queryString, data: passdata, cache: false,
				success: function(response,status)
				{	
					var ajaxresponse = response.split('^'); 
					if(ajaxresponse[0] == '1')
					{
						totalleadcount = ajaxresponse[1];// alert(totalleadcount);
						totallooprun = ajaxresponse[2]; 
						progressbar();
						transferleadloop();
					}
					else
					{
						messagebox.html(scripterror());
					}		
				}, 
				error: function(a,b)
				{
					messagebox.html(scripterror());
				}
			});		
		}
		else 
		 return false;
	}
}


function transferleadloop()
{
	currentlooprun++;
	var leadids = $('#hiddenid');
	var todealer =  $('#todealer'); 
	var messagebox = $('#messagebox');
	var leadidarray1 = leadids.val();
	leadidarray2 = leadidarray1.split(',',5);
	leadidarray3 =  leadidarray1.replace(leadidarray2+',','');
	leadids.val(leadidarray3);
	var passdata = "&submittype=transferleads&leadids="+leadidarray2+"&todealerid="+todealer.val()+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/bulkleadtransfer.php";
	ajaxobjext41 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^'); //alert(ajaxresponse)
			if(ajaxresponse[0] == '1')
			{
				if(currentlooprun < totallooprun)
				{
					totaltransfered = (totaltransfered * 1) + (ajaxresponse[1] * 1); //alert(totalcount);
					transferleadloop()
					progressbar();
				}
				else if(currentlooprun == totallooprun)
				{
					progressbar();
					totaltransfered = (totaltransfered * 1) + (ajaxresponse[1] * 1);
					var message = totaltransfered +' '+ 'Leads Tansfered Successfully.';
					messagebox.html(successmessage(message));
					$("#progressbar").hide();
					$("#abort").hide();
					$("#tabgroupgridc1_1").html('');
					$('#todealer').val('');
					$('#selectall').attr('checked',false);
				}
				else
				{
					totaltransfered = (totaltransfered * 1) + (ajaxresponse[1] * 1);
					var message = totaltransfered +' '+ 'Leads Tansfered Successfully.';
					messagebox.html(successmessage(message));
					$("#tabgroupgridc1_1").html('');
					$("#progressbar").hide();
					$("#abort").hide();
					$('#todealer').val('');
					$('#selectall').attr('checked',false);
				}
			}
			else
			{
				messagebox.html(scripterror());
			}	
			
		}, 
		error: function(a,b)
		{
			messagebox.html(scripterror());
		}
	});			
}


function progressbar()
{
	$("#progressbar").show();
	$("#abort").show();
	percentage = Math.round((currentlooprun/totallooprun)*100); 
	$("#progressbar-in").width(percentage + '%');
	currentprocessed = (currentlooprun * 5 < totalleadcount)?(currentlooprun * 5):totalleadcount; 
	$("#progressbar-data").html(currentprocessed + "/" + totalleadcount);
}


function abortfilterajaxprocess()
{
	
	ajaxobjext39.abort();
	$('#tabgroupgridc1_1').html('<table width="100%" border="1" cellpadding="2" cellspacing="0" id="gridtablelead"><tr class="gridheader"><td width="2%" class="tdborderlead">&nbsp;</td><td width="5%" class="tdborderlead">Sl No</td><td width="6%" class="tdborderlead">Lead Id</td> <td width="9%" class="tdborderlead">Lead Date</td><td width="9%" class="tdborderlead">Product</td><td width="8%" class="tdborderlead">Company</td><td width="7%" class="tdborderlead">Contact</td><td width="6%" class="tdborderlead">Cell</td><td width="12%" class="tdborderlead">Emailid</td><td width="10%" class="tdborderlead">District</td><td width="10%" class="tdborderlead">State</td><td width="7%" class="tdborderlead">Dealer</td><td width="20%" class="tdborderlead">Manager</td></tr></table>');
	//$("#messagebox").val(errormessage('Process Aborted'));
}


function abortleadtransferajaxprocess()
{
	ajaxobjext41.abort();
	$("#progressbar").hide();
	var message = totaltransfered +' '+ 'Leads Tansfered Successfully.';
	$('#messagebox').html(successmessage(message));
	$("#tabgroupgridc1_1").html('');
	$('#todealer').val('');
	$("#abort").hide();
	$('#selectall').attr('checked',false);
}