// JavaScript Document

var totalsent;
var totallooprun ;
var currentlooprun;
var totalleadcount;

function insertAtCursor(areaId) 
{
    var txtarea = document.getElementById(areaId);
	var text = $('#pickfield').val();
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie")
	{ 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
		countsmslength();
    }
    else if (br == "ff") 
	{
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
		countsmslength();
    }
    txtarea.scrollTop = scrollPos;
}

function filtering(command)
{
	var form = $("#filterform");
	var msg_box = $("#msg_box2");
	var textfield = $("#searchcriteria").val();
	var subselection = $('input[name=databasefield]:checked').val();
	var datatype =  $('input[name=datatype]:checked').val();
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
		$("#hiddenfromdate").val($("#fromdate").val());
		$("#hiddentodate").val($("#todate").val());
		var selectedvalue = $('#productid'); //alert(sel.value)
		$("#hiddenproductid").val($("#productid").val());
		// selected optgroup label 
		var grouplabel= $('#productid :selected').parents('OPTGROUP').attr('label');
	
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
		$('#tabgroupgridc1_1').html(processing()+'  '+ '<span onclick = "abortsendsmsajaxprocess()" class="abort">(STOP)</span>');
		// Remove the progress bar if it's there.
		$("#progressbar").hide();
		$("#abort").hide();
		
		var passdata = "&submittype=filter&fromdate=" + encodeURIComponent($("#DPC_fromdate").val()) + "&todate=" + encodeURIComponent($("#DPC_todate").val()) + "&dealerid=" + encodeURIComponent($("#dealerid").val()) + "&givenby=" + encodeURIComponent($("#givenby").val()) + "&productid=" + encodeURIComponent($("#productid").val()) + "&leadstatus=" + encodeURIComponent($("#leadstatus").val()) + "&filter_followupdate1=" + encodeURIComponent(filter_followupdate1) + "&filter_followupdate2=" + encodeURIComponent(filter_followupdate2) + "&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus)+"&searchtext="+ encodeURIComponent($("#srchhiddenfield").val())+"&subselection="+encodeURIComponent($("#subselhiddenfield").val())+"&datatype="+encodeURIComponent($("#datatypehiddenfield").val())+"&followedby="+encodeURIComponent($("#followedbyhidden").val())+"&leadsource="+encodeURIComponent($("#hiddensource").val())+"&grouplabel="+grouplabel+"&followupcheck="+followupcheck+"&remarks="+$("#remarks").val()+"&dummy=" + Math.floor(Math.random()*10230000000)  ;
		var queryString = "../ajax/bulksms.php";
		//alert(passdata)
		ajaxobjext42 = $.ajax(
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
						//document.getElementById("gridprocessrf").innerHTML = '';
					}
					else
					{
						$("#tabgroupgridc1_1").html(scripterror1());
					}
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
			var checkbox = 'smscheckbox' + i;
			$('#' + checkbox).attr('checked',true) ;
		}
		$('#selectedleadscount').html(totalcheckedcount + ' .');
	}
	else if(selecttype == 'all' && $('#selectall').is(':checked') == false)
	{
		for(var i = 1;i <= leadcount; i++)
		{
			var checkbox = 'smscheckbox' + i;
			$('#' + checkbox).attr('checked',false) ;
		}
		$('#selectedleadscount').html(totalcheckedcount + ' .');
	}
	else if(selecttype == 'countselected')
	{
		for(var i = 1;i <= leadcount; i++)
		{
			var checkbox = 'smscheckbox' + i;
			if($('#'+checkbox).is(':checked') == true)
			totalcheckedcount++;
		}
		$('#selectedleadscount').html(totalcheckedcount + ' .');
	}
}


// Function to send sms

function showmessagedetails()
{
	var checkedcount = 0;
	var messagebox = $('#messagebox');
	var leadids = $('#hiddenid'); 
	var leadcount = $('#leadcount').val();
	var smstext = $('#smstext');
	totalsent = 0;
	totallooprun = 0;
	currentlooprun = 0;
	totalleadcount = 0;
	var leadidarray = new Array();
	//alert(leadcount)
	if(leadcount == '')
	{
		messagebox.html(errormessage('There are no Leads to  Send SMS.'));return false;
	}
	else
	{	
		for(var i = 1;i <= leadcount; i++)
		{ 
			var checkboxcheck = 'smscheckbox' + i; 
			if($('#'+checkboxcheck).is(':checked') == true)
			{
				checkedcount++; 
				if(leadidarray == '')
				{
					leadidarray = leadidarray + $('#'+checkboxcheck).val();
				
				}
				else
				{
					leadidarray = leadidarray + ',' + $('#'+checkboxcheck).val();
				}
			}
		}
		$('#totalselected').val(checkedcount);
		if(checkedcount == '0')
		{
			messagebox.html(errormessage('Please Select Leads to Send SMS .'));return false; 
		}
		
		else if(smstext.val() == '')
		{
			messagebox.html(errormessage('Please Enter SMS Text.'));return false;
		}
		else
		{
			leadids.val(leadidarray); 
			messagebox.html('');
			//$('#smscontent').html(processing());
			var passdata = "&submittype=getalldetails&leadids="+leadidarray+"&smstext="+smstext.val()+"&dummy=" + Math.floor(Math.random()*100032680100);
			var queryString = "../ajax/bulksms.php";
			ajaxobjext43 = $.ajax(
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
							$('#smscontent').html(ajaxresponse[1]);
							$('#hiddentotalmessages').val(ajaxresponse[2]);
						}
						else
						{
							messagebox.html(scripterror());
						}
					}
				}, 
				error: function(a,b)
				{
					messagebox.html(scripterror());
				}
			});	
		}
	}	
}


function sendsms()
{
	var checkedcount = 0;
	var messagebox = $('#messagebox');
	var leadids = $('#hiddenid'); 
	var leadcount = $('#leadcount').val();
	var smstext = $('#smstext');
	totalsent = 0;
	totallooprun = 0;
	currentlooprun = 0;
	totalleadcount = 0;
	var leadidarray = new Array();
	for(var i = 1;i <= leadcount; i++)
	{ 
		var checkbox = 'smscheckbox' + i;
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
		messagebox.html(errormessage('There are no Leads to be Send SMS.'));
		return false;
	}
	else if(checkedcount == '0')
	{
		messagebox.html(errormessage('Please Select Leads to Send SMS .'));
		return false;		
	}
	
	else if(smstext.val() == '')
	{
		messagebox.html(errormessage('Please Enter SMS Text.'));
		return false;
	}
	else
	{
		$('#hiddenerrormessages').val('');
		var text = "Are you sure you want to send SMS to the Selected Leads as '" +smstext.val() +"' ??";
		var confirmation = confirm(text);
		if(confirmation)
		{  //alert(leadidarray)
			leadids.val(leadidarray); 
			messagebox.html('');
			var passdata = "&submittype=getloopingcount&checkedcount="+checkedcount+"&dummy=" + Math.floor(Math.random()*100032680100);
			var queryString = "../ajax/bulksms.php";
			ajaxobjext44 = $.ajax(
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
							totalleadcount = ajaxresponse[1];// alert(totalleadcount);
							totallooprun = ajaxresponse[2]; 
							progressbar();
							sendsmsloop();
						}
						else
						{
							messagebox.html(scripterror());
						}		
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

function sendsmsloop()
{
	currentlooprun++;
	var leadids = $('#hiddenid');
	var smstext =  $('#smstext'); 
	var messagebox = $('#messagebox');
	var leadidarray1 = leadids.val();
	leadidarray2 = leadidarray1.split(',',5);
	leadidarray3 =  leadidarray1.replace(leadidarray2+',','');
	leadids.val(leadidarray3);
	var passdata = "&submittype=sendsms&leadids="+leadidarray2+"&smstext="+smstext.val()+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/bulksms.php";
	ajaxobjext45 = $.ajax(
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
				var ajaxresponse = response.split('^'); //alert(ajaxresponse)
				if(ajaxresponse[0] == '1')
				{
					if(currentlooprun < totallooprun)
					{
						totalsent = (totalsent * 1) + (ajaxresponse[1] * 1); //alert(totalsent);
						sendsmsloop()
						progressbar();
					}
					else if(currentlooprun == totallooprun)
					{
						progressbar();
						totalsent = (totalsent * 1) + (ajaxresponse[1] * 1);
						var message = totalsent +' '+ 'SMSes sent Succesfully.';
						messagebox.html(successmessage(message));
						$("#tabgroupgridc1_1").html('');
						$('#smstext').val('');
						$("#abort").hide();
						$('#selectall').attr('checked',false);
						$('#leadcount').val('');
						$('#sms-error').html('');
						$('#selectedleadscount').html('');
						$('#totalleads').html('');
						$().colorbox.close();
					}
					else
					{
						totalsent = (totalsent * 1) + (ajaxresponse[1] * 1);
						var message = totalsent +' '+ 'SMSes sent Succesfully.';
						messagebox.html( successmessage(message));
						$("#tabgroupgridc1_1").html('');
						$('#smstext').val('');
						$("#abort").hide();
						$('#selectall').attr('checked',false);
						$('#leadcount').val('');
						$('#sms-error').html('');
						$('#selectedleadscount').html('');
						$('#totalleads').html('');
						$().colorbox.close();
					}
				}
				else
				{
					messagebox.html(scripterror());
				}		
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


function resetform()
{
	$('#smstext').val('');
	$('#messagebox').html('');
	$("#progressbar").hide();
}

function countsmslength()
{
	var smstext = $('#smstext').val();
	if(smstext.length <= '160')
		$('#sms-error').html('SMS Text Length : ' + smstext.length);
	else
	{
		$('#sms-error').html('SMS Text Length : ' + redtext(smstext.length));
	}
}


function abortsendsmsajaxprocess()
{
	ajaxobjext42.abort();
	$('#tabgroupgridc1_1').html('<table width="100%" border="1" cellpadding="2" cellspacing="0" id="gridtablelead"><tr class="gridheader"><td width="2%" class="tdborderlead">&nbsp;</td><td width="5%" class="tdborderlead">Sl No</td><td width="6%" class="tdborderlead">Lead Id</td> <td width="9%" class="tdborderlead">Lead Date</td><td width="9%" class="tdborderlead">Product</td><td width="8%" class="tdborderlead">Company</td><td width="7%" class="tdborderlead">Contact</td><td width="6%" class="tdborderlead">Cell</td><td width="12%" class="tdborderlead">Emailid</td><td width="10%" class="tdborderlead">District</td><td width="10%" class="tdborderlead">State</td><td width="7%" class="tdborderlead">Dealer</td><td width="20%" class="tdborderlead">Manager</td></tr></table>');
}


function abortsendingsms()
{
	ajaxobjext45.abort();
	$("#abort").hide();
	var message = totalsent +' '+ 'SMSes sent Succesfully.';
	$('#messagebox').html(successmessage(message));
	$("#tabgroupgridc1_1").html('');
	$('#smstext').val('');
	$('#selectall').is('checked',false);
	$('#leadcount').val('');
	$('#sms-error').html('');
	$('#selectedleadscount').html('');
	$('#totalleads').html('');
	$().colorbox.close();
}


function validate()
{
	var returnvalue = showmessagedetails();
	//alert($("#hiddenerrormessages").val())
	if(returnvalue != false)
	{
		$("").colorbox({ inline:true, href:"#inline_example1" , onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}