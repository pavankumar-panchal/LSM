var mcaarray = new Array();
var totalarray = new Array();
var mcaarray1 = new Array();
var mcaarray2 = new Array();
var mcaarray3 = new Array();
var mcaarray4 = new Array();
var mcaarray5 = new Array();


var process1;var process2;var process3;var process4;
var contactarray = '';


function gettotalmcalist()
{
	var form = $('#customerselectionprocess');
	$("#customerselectionprocess").html(processing());
	var passData = "switchtype=getmcacount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/mcamaster.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$("#totalcount").html(response['count']);
				refreshmcaarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}



function refreshmcaarray(mcacount)
{
	var form = $('#customerselectionprocess');
	var totalmcacount = mcacount;
	var limit = Math.round(totalmcacount/4);
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;

	var form = $('#cardsearchfilterform');
	var passData = "switchtype=generatemcalist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=generatemcalist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=generatemcalist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=generatemcalist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	

	 $('#customerselectionprocess').html(processing());
	queryString = "../ajax/mcamaster.php";
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				for( var i=0; i<response.length; i++)
				{
					mcaarray1[i] = response[i];
				}
				process1 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/mcamaster.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData1, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					mcaarray2[i] = response[i];
				}
				process2 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

	queryString = "../ajax/mcamaster.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData2, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					mcaarray3[i] = response[i];
				}
				process3 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/mcamaster.php";
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData3, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					mcaarray4[i] = response[i];
				}
				process4 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

}

function compilecustomerarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true  )
	{
		
		mcaarray = mcaarray1.concat(mcaarray2.concat(mcaarray3.concat(mcaarray4)));
		flag = true;
		$("#customerselectionprocess").html(successmessage('All Customers...'))
		getmcalist();
		
	}
	else
	return false;
}

function searchcustomerarray(callstatus)
{
	var form = $("#searchfilterform");
	var form = $("#submitform");
	var error = $("#filter-form-error");
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); return false;	}
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent($("#region2").val())+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val())+"&type2=" +encodeURIComponent($("#type2").val()) + "&category2=" + encodeURIComponent($("#category2").val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
		$('#customerselectionprocess').html(getprocessingimage());
		queryString = "../ajax/customer.php";
		ajaxcall3 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse;
					if(response == '')
						{
							$('#filterdiv').show();
							customersearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customersearcharray[i] = response[i];
							}
							
							getcustomerlistonsearch();
							$("#customerselectionprocess").html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '))
							$("#totalcount").html('0');
							error.html(errormessage('No datas found to be displayed.')); 
						}
						else
						{
							$('#filterdiv').hide();//alert(response);
							customersearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customersearcharray[i] = response[i];
							}
							flag = false;
							getcustomerlistonsearch();
							$("#customerselectionprocess").html(successmessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '));
							$("#totalcount").html(customersearcharray.length);
							$("#filter-form-error").html();

						}
				}
			}, 
			error: function(a,b)
			{
				$("#customerselectionprocess").html(scripterror());
			}
		});	
}

function getcustomerlistonsearch()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customersearcharray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customersearcharray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function getmcalist()
{	
	var form = $("#submitform" );
	var selectbox = $('#mcalist');
	var numberofmcalist = mcaarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofmcalist > actuallimit)?actuallimit:numberofmcalist;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = mcaarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}



function displayalcustomer()
{	
	var form = $("#submitform" );
	flag = true;
	var selectbox = $('#customerlist');
	$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	$('#totalcount').html(customerarray.length);
}



function selectfromlist()
{
	var selectbox = $("#mcalist option:selected").val();
	$('#detailsearchtext').val($("#mcalist option:selected").text());
	$('#detailsearchtext').select();
	//$('#filterdiv').hide();
	//$('#tabgroupgridwb1').html('');
	detailstoform(selectbox);	
}

function selectacompany(input)
{
	var selectbox = $('#mcalist');
	if(flag == true)
	{
		if(input == "")
		{
			getmcalist();
		}
		else
		{
			$('option', selectbox).remove();
			var options = selectbox.attr('options');
			
			var addedcount = 0;
			for( var i=0; i < mcaarray.length; i++)
			{
					if(input.charAt(0) == "%")
					{
						withoutspace = input.substring(1,input.length);
						pattern = new RegExp(withoutspace.toLowerCase());
						comparestringsplit = mcaarray[i].split("^");
						comparestring = comparestringsplit[1];
					}
					else
					{
						pattern = new RegExp("^" + input.toLowerCase());
						comparestring = mcaarray[i];
					}
					var result1 = pattern.test(trimdotspaces(mcaarray[i]).toLowerCase());
					var result2 = pattern.test(mcaarray[i].toLowerCase());
					if(result1 || result2)
					{
						var splits = mcaarray[i].split("^");
						options[options.length] = new Option(splits[0], splits[1]);
						addedcount++;
						if(addedcount == 100)
							break;
					}
			}
		}
	}
	else if(flag == false)
	{
		if(input == "")
		{
			getcustomerlistonsearch();
		}
		else
		{
			$('option', selectbox).remove();
			var options = selectbox.attr('options');
			var addedcount = 0;
			for( var i=0; i < customersearcharray.length; i++)
			{
					if(input.charAt(0) == "%")
					{
						withoutspace = input.substring(1,input.length);
						pattern = new RegExp(withoutspace.toLowerCase());
						comparestringsplit = customersearcharray[i].split("^");
						comparestring = comparestringsplit[1];
					}
					else
					{
						pattern = new RegExp("^" + input.toLowerCase());
						comparestring = customersearcharray[i];
					}
					var result1 = pattern.test(trimdotspaces(customersearcharray[i]).toLowerCase());
					var result2 = pattern.test(customersearcharray[i].toLowerCase());
					if(result1 || result2)
					{
						var splits = customersearcharray[i].split("^");
						options[options.length] = new Option(splits[0], splits[1]);
						addedcount++;
						if(addedcount == 100)
							break;
					}
			}
		}
	}
	
}

function mcasearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcompany('up');
	else if(KeyID == 40)
		scrollcompany('down');
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectacompany(input);
	}
}

function scrollcompany(type)
{
	var selectbox = $('#mcalist');
	var totalcus = $("#mcalist option").length;
	var selectedcus = $("select#mcalist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#mcalist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#mcalist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}


function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycustomerid(input);
	}
}

function detailstoform(lastslno)
{
	var form = $('#customerselectionprocess');
	$('#lastslno').val(lastslno);
	var passData = "switchtype=detailstoform&lastslno=" + $('#lastslno').val() + "&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/mcamaster.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$("#form_companyname").html(response['company']);
					$("#form_emailid").html(response['emailid']);
					$("#form_address").html(response['address']);
					$("#form_address1").html(response['address1']);
					$("#form_city").html(response['city']);
					$("#form_pincode").html(response['pincode']);
					$("#form_state").html(response['state']);
					$("#form_roccode").html(response['roccode']);
					$("#form_registrationnumber").html(response['registrationnumber']);
					$("#form_category").html(response['category']);
					$("#form_subcategory").html(response['subcategory']);
					$("#form_class").html(response['class']);
					$("#form_numberofmembers").html(response['numberofmembers']);
					$("#form_incorporateddate").html(response['incorporateddate']);
					$("#form_agmdate").html(response['agmdate']);
					$("#form_listingtype").html(response['listingtype']);
					$("#form_balancesheetdate").html(response['balancesheetdate']);
					$("#form_authorisedcapital").html(response['authorisedcapital']);
					$("#form_paidupcapital").html(response['paidupcapital']);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}

function displayDiv()
{
	if($('#filterdiv').is(':visible'))
	{
		$('#filterdiv').hide();
	}
	else
	{
		$('#filterdiv').show();
	}
}