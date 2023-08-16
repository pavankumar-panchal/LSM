// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html( processing());
	var passdata ="&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata);
	var queryString = "../ajax/viewleads.php";
	ajaxobjext81 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response1 = response.split("|^|"); //alert(response1);
			if(response1[0] == '1')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html('<font color="#FFFFFF"><strong>&nbsp; List of Leads => [ (' + response1[3] +' Records)] </strong></font>');
			}
			else if(response1[0] == '2')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html('<font color="#FFFFFF"><strong>&nbsp; List of Leads => [ (' + response1[3] +' Records)]</strong></font>');
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
	var field = $("#DPC_fromdate");
	if(compare2dates(($("#DPC_fromdate").val()),($("#DPC_todate").val())) == false)
	{ msg_box.innerHTML = errormessage("From date cannot be greater than To date."); form.fromdate.focus(); return false;}

	if($("#considerfollowup").is(':checked') == true)
	{
		if(compare2dates(($("#DPC_filter_followupdate1").val()),($("#DPC_filter_followupdate2").val())) == false)
		{ msg_box.innerHTML = errormessage("Followup From date cannot be greater than Followup To date."); form.filter_followupdate1.focus(); return false;}
		
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
	if($("#dropterminatedstatus").is(':checked') == true)
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
		$("#hiddenfromdate").val($("#DPC_fromdate").val());
		$("#hiddentodate").val($("#DPC_todate").val());
		$("#hiddenproductid").val($("#productid").val());
		$("#hiddendealerid").val($("#dealerid").val());
		$("#hiddenleadstatus").val($("#leadstatus").val());
		$("#gridprocessf").html(processing() +'  '+ '<span onclick = "abortviewleadsfilterajaxprocess(\'initial\')" class="abort">(STOP)</span>');
		leadgridtab2('2','tabgroupleadgrid','searchresult');
		var passdata = "&submittype=filter&fromdate=" + encodeURIComponent($("#DPC_fromdate").val()) + "&todate=" + encodeURIComponent($("#DPC_todate").val()) + "&dealerid=" + encodeURIComponent($("#dealerid").val()) + "&productid=" + encodeURIComponent($("#productid").val()) + "&leadstatus=" + encodeURIComponent($("#leadstatus").val()) + "&filter_followupdate1=" + encodeURIComponent(filter_followupdate1) + "&filter_followupdate2=" + encodeURIComponent(filter_followupdate2) + "&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus) +"&dummy==" + Math.floor(Math.random()*10230000000) ; //alert(passdata);
		var queryString = "../ajax/viewleads.php";
		ajaxobjext82 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var response2 = response.split("|^|");  //alert(response);
				if(response2[0] == '1')
				{
					$("#tabgroupgridf1_1").html(response2[1]);
					$("#getmorelinkf1").html(response2[2]);
					$("#gridprocessf").html(' <font color="#FFFFFF">=> Filter Applied (' + response2[3] +' Records)</font>');
					msg_box.html("");
				}
				else if(response2[0] == '2')
				{
					$("#msg_box2").html(errormessage(response2[1]));
					$("#gridprocessf").html('');
				}
				else if(response2[0] == '3')
				{
					$("#tabgroupgridf1_1").html(errormessage(response2[1]));
					$("#gridprocessrf").html('');
				}
				else
				{
					$("#gridprocessf").html(scripterror1());
				}
			}, 
			error: function(a,b)
			{
				$("#gridprocessf").html(scripterror1());
			}
		});	
	}
}


function getmorerecords(startlimit,slnocount,showtype,type)
{
	
	var form = $("#filterform");
	if($("#dropterminatedstatus").is(':checked') == true)
	{
		var dropterminatedstatus = 'true';
	}
	else
	{
		var dropterminatedstatus = 'false';
	}
	if(type == 'lead')
	{
		$("#gridprocess").html(processing());
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
		var queryString = "../ajax/viewleads.php"; //alert(passdata);
		ajaxobjext83 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					$('#resultgrid').html( $('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelink').html(ajaxresponse[2]);
					$("#gridprocess").html(' <font color="#FFFFFF"><strong>&nbsp; List of Leads => [(' + ajaxresponse[3] +' Records)] </strong></font>');
					
				}
				else if(ajaxresponse[0] == '2')
				{
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelink').html(ajaxresponse[2]);
					$("#gridprocess").html(' <font color="#FFFFFF"><strong>&nbsp; List of Leads => [(' + ajaxresponse[3] +' Records)]</strong></font>');
					
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
	else if(type == 'filter')
	{
		$("#gridprocessf").html(processing()+'  '+ '<span onclick = "abortviewleadsfilterajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&fromdate=" + encodeURIComponent($("#hiddenfromdate").val()) + "&todate=" + encodeURIComponent($("#hiddentodate").val()) + "&dealerid=" + encodeURIComponent($("#hiddendealerid").val()) + "&givenby=" + encodeURIComponent($("#hiddengivenby").val()) + "&productid=" + encodeURIComponent($("#hiddenproductid").val()) + "&leadstatus=" + encodeURIComponent($("#hiddenleadstatus").val()) + "&filter_followupdate1=" + encodeURIComponent($("#filter_followupdate1hdn").val()) + "&filter_followupdate2=" + encodeURIComponent($("#filter_followupdate2hdn").val()) + "&dropterminatedstatus=" + encodeURIComponent(dropterminatedstatus) +"&dummy==" + Math.floor(Math.random()*10230000000);
		
		var queryString = "../ajax/viewleads.php"; //alert(passdata);
		ajaxobjext84 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					$('#resultgridf1').html($('#tabgroupgridf1_1').html());
					$('#tabgroupgridf1_1').html( $('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelinkf1').html(ajaxresponse[2]);
					$("#gridprocessf").html('<font color="#FFFFFF">=> Filter Applied (' + ajaxresponse[3] +' Records)</font>');
					
				}
				else if(ajaxresponse[0] == '2')
				{
					$('#resultgridf1').html( $('#tabgroupgridf1_1').html());
					$('#tabgroupgridf1_1').html($('#resultgridf1').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelinkf1').html(ajaxresponse[2]);
					$("#gridprocessf").html('<font color="#FFFFFF"> => Filter Applied (' + ajaxresponse[3] +' Records)</font>');
				}
				else
				{
					$("#gridprocessf").html(scripterror1());
				}
			}, 
			error: function(a,b)
			{
				$("#gridprocessf").html(scripterror1());
			}
		});
	}
}



function leadgridtab2(activetab,groupname,activetype)
{
	var totaltabs = 2;
	var activetabclass = "grid-active-leadtabclass";
	var tabheadclass = "grid-leadtabclass";
	for(var i=1 ; i <= totaltabs ; i++)
	{
		var tabhead = groupname + 'h' + i;  
		var tabcontent = groupname + 'c' + i;
		if(i == activetab)
		{
			//alert(document.getElementById(tabhead).className); alert(tabheadclass);
			$('#'+tabhead).attr('class',activetabclass);
			$('#'+tabcontent).show();
			if(activetype == 'default')
			{
				griddata('');
			}
			
		}
		else 
		{
			$('#'+tabhead).attr('class',tabheadclass);
			$('#'+tabcontent).hide();
		}
	}
}

// Grid to form for a lead

function gridtoform(id)
{
	$('#msg_box').html('');
	newfollowup();
	var passdata = "&submittype=gridtoform&form_recid=" + id+"&dummy=" + Math.floor(Math.random()*100032680100); 
	var queryString = "../ajax/viewleads.php";
	ajaxobjext85= $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response3 = response.split("|^|");
			if(response3[0] == '1')
			{
				$('#form_recid').val(response3[1]);
				$('#hiddenid').val(response3[3]); //alert(document.getElementById('hiddenid').value );
				$('#id').html(response3[2] + ' ' + '[' + response3[3] + ']');
				$('#hiddencompany').val(response3[2] + ' ' + '[' + response3[3] + ']');
				$('#contactperson').html(response3[4]);
				$('#hiddencontact').val(response3[4]);
				$('#address').html(response3[5]);
				$('#hiddenaddress').val(response3[5]);
				$('#district').html(response3[6] + ' ' + '/' + ' ' + response3[7]);
				$('#hiddendistrictstate').val(response3[6] + ' ' + '/' + ' ' + response3[7]);
				$('#stdcode').html( response3[8]);
				$('#hiddenstdcode').val(response3[8]);
				$('#phone').html(response3[9]);
				$('#hiddenphone').val(response3[9]);
				$('#cell').html(response3[10]);
				$('#hiddencell').val(response3[10]);
				$('#emailid').html(response3[11]);
				$('#hiddenemailid').val(response3[11]);
				$('#referencetype').html(response3[12] + ' ' + '[' + response3[13] + ']');
				$('#hiddenreference').val(response3[12] + ' ' + '[' + response3[13] + ']');
				$('#givenby1').html(response3[14]); 
				$('#hiddengivenby').val(response3[14]);
				$('#dateoflead').html(response3[15]);
				$('#hiddendateoflead').val(response3[15]);
				$('#dealerviewdate').html(response3[16]);
				$('#hiddendealerviewdate').val(response3[16]);
				$('#product1').html(response3[17]);
				$('#hiddenproduct').val(response3[17]);
				$('#dealer1').html(response3[18]);
				$('#hiddendealer').val( response3[18]);
				$('#manager').html(response3[20]);
				$('#hiddenmanager').val(response3[20]); //alert(response3[21]);
				autoselect('form_leadstatus',response3[21])
				$('#leadremarks').html(response3[24]);
				jumpToAnchor("leadview");
				showfollowups(response3[1]);
				//newfollowup();
			}
			else
			{
				$('$msg_box').html('');
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});
}



function showfollowups(leadid)
{
	var form_recid = $("#form_recid");
	$("#followupmessage").html(processing());
	var passdata = "&submittype=showfollowups&form_recid=" + form_recid.val() +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/viewleads.php"; //alert(passdata);
	ajaxobjext86 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			var response6 = response.split("^"); //alert(response6);
			if(response6[0] == '1')
			{
				$("#smallgrid").html(response6[1]);
				$("#followupmessage").html('');
				gridtab3('1','tabgroupgrid','followup');
			}
			else
			{
				$("#followupmessage").html(scripterror1());
			}
		}, 
		error: function(a,b)
		{
			$("#followupmessage").html(scripterror1());
		}
	});
}

function followuptoform(followupid)
{
	var passdata = "&submittype=followuptoform&followupid=" + followupid +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/viewleads.php";
	ajaxobjext87 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{
			var response7 = response.split("|^|");
			if(response7[0] == '1')
			{
				$('#form_leadremarks').val(response7[1]);
				cleantext('followupdate', 'dd-mm-yyyy');
				$('#followupdate').val(response7[2]);
				$('#followupmessage').html('');
			}
			else
			{
				$('#followupmessage').html(scripterror());
			}				
		}, 
		error: function(a,b)
		{
			$('#followupmessage').html(scripterror());
		}
	});
}


function newfollowup()
{
	$("#form_leadremarks").val('');
	$("#followupdate").val('');
	$("#followupmessage").html('');
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

function gridtab3(activetab,groupname,activetype)
{
	var totaltabs = 3;
	var activetabclass = "grid-active-tabclass";
	var tabheadclass = "grid-tabclass";
	for(var i=1 ; i <= totaltabs ; i++)
	{
		var tabhead = groupname + 'h' + i;
		var tabcontent = groupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).attr('class',activetabclass);
			$('#'+tabcontent).show();
			$('#hiddenactivetype').val(activetype);
			if(activetype == 'updatelogs')
			{
				tabgridcontent('updatelogs',tabcontent);
			}
			else if(activetype == 'transferlogs')
			{
				tabgridcontent('transferlogs',tabcontent);
			}
			else if(activetype == 'followup')
			{
				tabgridcontent('followup',tabcontent);
			}
			
		}
		else 
		{
			$('#'+tabhead).attr('class',tabheadclass) ;
			$('#'+tabcontent).hide();
		}
	}	
}

function tabgridcontent(referencetablename,contentdiv)
{
	var id = $('#hiddenid').val(); //alert(id);
	var queryString = "../ajax/viewleads.php";
	if(id != '')
	{
		if(referencetablename == 'updatelogs')
		{
			$('#tabgroupgridc1_2').html(processing());
			var passdata = "&submittype="+referencetablename+"&id="+id+"&dummy=" + Math.floor(Math.random()*10230000000);
		}
		else if(referencetablename == 'transferlogs')
		{
			$('#tabgroupgridc1_3').html(processing());
			var passdata = "&submittype="+referencetablename+"&id="+id+"&dummy=" + Math.floor(Math.random()*10230000000);
		}
		else if(referencetablename == 'followup')
		{
			var passdata = "&submittype=showfollowups&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);
		}
		ajaxobjext88 = $.ajax(
		{
			
			type: "POST",url: queryString, data: passdata, cache: false,
			success: function(response,status)
			{	
				var ajaxresponse = response.split('^'); //alert(ajaxresponse);
				$('#tabgroupgridc1_2').html('');
				$('#tabgroupgridc1_3').html('');
				//document.getElementById('msg_box').innerHTML = '';
				if(ajaxresponse[0] == '1')
				{					
					if(contentdiv == 'tabgroupgridc2')
					{
						$('#tabgroupgridc1_2').html(ajaxresponse[1]);  
						$("#getmorelink2").html(ajaxresponse[2]);
					}
					else if(contentdiv == 'tabgroupgridc3')
					{
						$('#tabgroupgridc1_3').html(ajaxresponse[1]);  
						$("#getmorelink3").html(ajaxresponse[2]);
					}
					else if(contentdiv == 'tabgroupgridc1')
					{
						$("#smallgrid").html(ajaxresponse[1]);
						$("#followupmessage").html('');
					}
				}
				else if(ajaxresponse[0] == '2')
				{
					if(contentdiv == 'tabgroupgridc2')
					{
						$('#tabgroupgridc1_2').html(ajaxresponse[1]);  
						//document.getElementById("getmorelink2").innerHTML = ajaxresponse[2];
					}
					else if(contentdiv == 'tabgroupgridc3')
					{
						//document.getElementById('contentdiv').innerHTML = ajaxresponse[1]; 
						$('#tabgroupgridc1_3').html(ajaxresponse[1]);  
						//document.getElementById("getmorelink3").innerHTML = ajaxresponse[2];
					}
				}
				else
				{
					if(contentdiv == 'tabgroupgridc2')
					{
						$('#tabgroupgridc1_2').html(scripterror());
					}
					else if(contentdiv == 'tabgroupgridc3')
					{
						$('#tabgroupgridc1_3').html(scripterror());
					}
				}
				
			}, 
			error: function(a,b)
			{
				if(contentdiv == 'tabgroupgridc2')
				{
					$('#tabgroupgridc1_2').html(scripterror());
				}
				else if(contentdiv == 'tabgroupgridc3')
				{
					$('#tabgroupgridc1_3').html(scripterror());
				}
			}
		});		
	}
	else
	{
		$('#msg_box').html(errormessage("Please Select a Lead First"));
		return false;
	}
}


function getmorerecords2(startlimit,slnocount,showtype)
{
	//document.getElementById("gridprocess").innerHTML = processing();
	var id = $('#hiddenid').val();
	var passdata = "&submittype=transferlogs&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&id="+id+"&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
	var queryString = "../ajax/viewleads.php";
	ajaxobjext89 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^');//alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$('#resultgrid3').html( $('#tabgroupgridc1_3').html());
				$('#tabgroupgridc1_3').html($('#resultgrid3').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
				$('#getmorelink3').html(ajaxresponse[2]);
			}
			else
			{
				$("#getmorelink3").html(errormessage("No datas found to be displayed."));
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#resultgrid3").html(scripterror());
		}
	});		
}

function getmorerecords3(startlimit,slnocount,showtype)
{
	//document.getElementById("gridprocess").innerHTML = processing();
	var id = $('#hiddenid').val();
	var passdata = "&submittype=updatelogs&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&id="+id+"&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
	var queryString = "../ajax/viewleads.php";
	ajaxobjext90 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('^');//alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				//document.getElementById("gridprocess").innerHTML = '';
				$('#resultgrid2').html($('#tabgroupgridc1_2').html());
				$('#tabgroupgridc1_2').html($('#resultgrid2').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
				$('#getmorelink2').html(ajaxresponse[2]);
				//document.getElementById('totalcount').innerHTML = "Total Count :  " + ajaxresponse[3];
				
			}
			else
			{
				//document.getElementById("gridprocess").innerHTML = '';
				$("#getmorelink2").html(errormessage("No datas found to be displayed."));
			}
		}, 
		error: function(a,b)
		{
			$("#resultgrid2").html(scripterror());
		}
	});		
}

function newtog()
{
	$('#filterform').toggle();
}

function abortviewleadsfilterajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext82.abort();	
		$("#gridprocessf").html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext84.abort();
		$("#gridprocessf").html('');
	}
}