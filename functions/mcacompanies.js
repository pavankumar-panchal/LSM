var ajaxcall233 = null;
function refreshmcaarray()
{
	$('#searchhidden').val('false');
	var form = $('#customerselectionprocess');
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=generatemcalist&dummy=" + Math.floor(Math.random()*10054300000);
	$('#customerselectionprocess').html(processing());
	queryString = "../ajax/mcacompanies.php";
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
			else if(ajaxresponse.length > 0)
			{
				
				var response = ajaxresponse;
				var limitlist = 50;
				var selectbox = $('#mcalist');
				$('option', selectbox).remove();
				var options = selectbox.attr('options');
				for( var i=0; i<limitlist; i++)
				{
					var splits = response[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
				}
				$('#customerselectionprocess').html(successsearchmessage('All Data...'));
			}
			else
			{
				$('#customerselectionprocess').html('');
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}


function searchmcalist()
{
	  var form = $('#customerselectionprocess');
	  if($('#detailsearchtext').val() == '%')
	  {
		  typesearch = 'percentgesearch';
	  }
	  else
	  {
		 typesearch = '';
	  }
	  
	  if($('#searchhidden').val() == 'true')
	  {
		var searchtype = 'searchlist';
		var subselection = $("input[name='databasefield']:checked").val();
		var textfield = $("#detailsearchtext").val();
		var searchtextfield = $("#searchcriteria").val();
		var passData = "switchtype=advancesearch&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#mca_state").val())  + "&class=" +encodeURIComponent($("#mca_class").val())+ "&roccode=" +encodeURIComponent($("#mca_roccode").val()) + "&textfield=" +encodeURIComponent(textfield)  +"&paidupcapital=" +encodeURIComponent($("#mca_puc").val())+"&branch=" +encodeURIComponent($("#mca_branch").val())+ "&typesearch=" + typesearch + "&searchtype=" + searchtype + "&searchtextfield=" + searchtextfield+ "&dummy=" + Math.floor(Math.random()*10054300000);
	  }
	  else
	  {
		  
		var passData = "switchtype=searchmcalist&searchtext=" + $('#detailsearchtext').val() + "&typesearch=" + typesearch + "&dummy=" + Math.floor(Math.random()*10054300000);
	  }
	 // $('#customerselectionprocess').html(processing());
	  if(ajaxcall233 != null)
	  {
		   ajaxcall233.abort();
		  // $('#customerselectionprocess').html('');
	  }
	  queryString = "../ajax/mcacompanies.php";
	  ajaxcall233 = $.ajax(
	  {
		  type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		  success: function(ajaxresponse,status)
		  {	
			  var selectbox = $('#mcalist');
			  if(ajaxresponse == 'Thinking to redirect')
			  {
				  window.location = "../logout.php";
				  return false;
			  }
			  else if(ajaxresponse.length > 0)
			  {
				  var response = ajaxresponse;
				  var limitlist = (ajaxresponse.length <=50) ? ajaxresponse.length : 50;
				  $('option', selectbox).remove();
				  var options = selectbox.attr('options');
				  for( var i=0; i<limitlist; i++)
				  {
					  var splits = response[i].split("^");
					  options[options.length] = new Option(splits[0], splits[1]);
				  }
				//  $('#customerselectionprocess').html('');
			  }
			  else
			  {
				   $('option', selectbox).remove();
				    if($('#searchhidden').val() == 'true')
					{
						
					 $('#customerselectionprocess').html(errormessage("Search Result"  + '<span style="padding-left: 15px; height:20px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshmcaarray()"></span> '));
					}
					else
					{
					$('#customerselectionprocess').html(successsearchmessage('All Data...'));
					}
			  }
		  }, 
		  error: function(a,b)
		  {
			  $("#customerselectionprocess").html(scripterror());
		  }
	  });	

}


function mcasearch(e)
{ 
	//newentry();
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcompany('up');
	else if(KeyID == 40)
		scrollcompany('down');
	else if((KeyID >= '65' &&  KeyID <= '90') || (KeyID >= '97' &&  KeyID <= '122')|| (KeyID >= '48' &&  KeyID <= '57') ||(KeyID == '8') || (KeyID == '46') || (KeyID == '53'))
	{
		searchmcalist();
	}
	else
		return false;
}

function selectfromlist()
{
	var selectbox = $("#mcalist option:selected").val();
	$('#detailsearchtext').val($("#mcalist option:selected").text());
	$('#detailsearchtext').select();
	detailstoform(selectbox);	
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


function detailstoform(lastslno)
{
	$('#lastslno').val(lastslno);
	if($('#lastslno').val() == '')
	{
		return false;
	}
	else
	{
	  var form = $('#customerselectionprocess');
	  $('#customerdetailselection').html(processing());
	  var passData = "switchtype=detailstoform&lastslno=" + $('#lastslno').val() + "&dummy=" + Math.floor(Math.random()*10054300000);
	  queryString = "../ajax/mcacompanies.php";
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
					 // alert(response['addrowcount']);
					  if(response['addrowcount'] == null)
						  $('#converttoleadbtn').attr("disabled", true);
					  else
						  $('#converttoleadbtn').attr("disabled", false);
					  $('#customerdetailselection').html('');
					  $("#errormessage").html('');
					  $("#form_companyname").html(response['company']);
					  $("#form_emailid").html(response['emailid']);
					  $("#form_address").html(response['address']);
					  $("#form_address2").html(response['address2']);
					  $("#form_city").html(response['city']);
					  $("#form_pincode").html(response['pincode']);
					  $("#form_state").html(response['state']);
					  $("#form_roccode").html(response['roccode']);
					  $("#form_registrationnumber").html(response['registrationnumber']);
					  $("#form_category").html(response['category']);
					  $("#form_subcategory").html(response['subcategory']);
					  $("#form_class").html(response['class']);
					  $("#form_cin").html(response['cin']);
					  $("#form_incorporateddate").html(response['incorporateddate']);
					  $("#form_agmdate").html(response['agmdate']);
					  $("#form_listingtype").html(response['listingtype']);
					  $("#form_balancesheetdate").html(response['balancesheetdate']);
					  $("#form_authorisedcapital").html(response['authorisedcapital']);
					  $("#form_paidupcapital").html(response['paidupcapital']);
					  $("#directorinfo").html(response['grid']);
					  $("#address").val(response['addaddress']);
					  $("#place").val(response['addplace']);
					  $("#phone").val(response['addphone']);
					  $("#cell").val(response['addcell']);
					  $("#emailid").val(response['addemailid']);
					  $("#stdcode").val(response['stdcode']);
					  $("#addlastslno").val(response['addinfoid']);
					//  $("#state").val(response['addstate']);
						if(response['addstate'] == null)
							response['addstate'] = ''
					  autoselect('state',response['addstate']);
					  districtselect('form_district',response['adddistrict']);
					  $("#contactperson").val(response['name']);
				  }
			  }
		  }, 
		  error: function(a,b)
		  {
			  $("#customerselectionprocess").html(scripterror());
		  }
	  });	
	}
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

function selectfromlist()
{
	var selectbox = $("#mcalist option:selected").val();
	$('#detailsearchtext').val($("#mcalist option:selected").text());
	$('#detailsearchtext').select();
	detailstoform(selectbox);	
}

function newentry()
{
	$('#customerdetailselection').html('');
	$("#form_companyname").html('');
	$("#form_emailid").html('');
	$("#form_address").html('');
	$("#form_address2").html('');
	$("#form_city").html('');
	$("#form_pincode").html('');
	$("#form_state").html('');
	$("#form_roccode").html('');
	$("#form_registrationnumber").html('');
	$("#form_category").html('');
	$("#form_subcategory").html('');
	$("#form_class").html('');
	$("#form_numberofmembers").html('');
	$("#form_incorporateddate").html('');
	$("#form_agmdate").html('');
	$("#form_listingtype").html('');
	$("#form_balancesheetdate").html('');
	$("#form_authorisedcapital").html('');
	$("#form_paidupcapital").html('');
}


function advancesearch()
{
	  var form = $('#searchfilterform');
	  var subselection = $("input[name='databasefield']:checked").val();
	  var textfield = $("#detailsearchtext").val();
	  var searchtextfield = $("#searchcriteria").val();
	  $('#detailsearchtext').val('');
	  $('#searchhidden').val('true');
	  var searchtype = 'advancesearch';
	  var passData = "switchtype=advancesearch&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#mca_state").val())  + "&class=" +encodeURIComponent($("#mca_class").val())+ "&roccode=" +encodeURIComponent($("#mca_roccode").val()) + "&textfield=" +encodeURIComponent(textfield)+ "&searchtype=" +encodeURIComponent(searchtype) + "&searchtextfield=" +encodeURIComponent(searchtextfield) +"&paidupcapital=" +encodeURIComponent($("#mca_puc").val())+"&branch=" +encodeURIComponent($("#mca_branch").val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	 // var passData = "switchtype=advancesearch&searchtext=" + $('#searchcriteria').val() + "&typesearch=" + typesearch + "&dummy=" + Math.floor(Math.random()*10054300000);
	  $('#customerselectionprocess').html(processing());
	  if(ajaxcall233 != null)
	  {
		   ajaxcall233.abort();
		   $('#customerselectionprocess').html('');
	  }
	  queryString = "../ajax/mcacompanies.php";
	  ajaxcall233 = $.ajax(
	  {
		  type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		  success: function(ajaxresponse,status)
		  {	
		
			  if(ajaxresponse == 'Thinking to redirect')
			  {
				  window.location = "../logout.php";
				  return false;
			  }
			  else if(ajaxresponse.length > 0)
			  {
				  var response = ajaxresponse;
				  var limitlist = (ajaxresponse.length <=50) ? ajaxresponse.length : 50;
				  var selectbox = $('#mcalist');
				  $('option', selectbox).remove();
				  var options = selectbox.attr('options');
				  for( var i=0; i<limitlist; i++)
				  {
					  var splits = response[i].split("^");
					  options[options.length] = new Option(splits[0], splits[1]);
				  }
				  $('#customerselectionprocess').html(successmessage("Search Result"  + '<span style="padding-left: 15px; height:20px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshmcaarray()"></span> '));
				 $('#filterdiv').hide();
				 // clearfilter();
			  }
			  else
			  {
				  var selectbox = $('#mcalist');
				  $('option', selectbox).remove();
				  $('#customerselectionprocess').html(errormessage("Search Result"  + '<span style="padding-left: 15px; height:20px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshmcaarray()"></span> '));
				  $('#filterdiv').hide();
			  }
		  }, 
		  error: function(a,b)
		  {
			  $("#customerselectionprocess").html(scripterror());
		  }
	  });	

}


function clearfilter()
{
	$("#searchfilterform" )[0].reset();
}

function closefilter()
{
	$("#filterdiv" ).hide();
	//$('#searchhidden').val('false')
}


function districtselect(selectid,comparevalue)
{
	var code = $('#state').val();
	var outputselect = $('#districtdiv');
	var passdata = "&switchtype=form_state&code=" + code +"dummy=" + Math.floor(Math.random()*100032680100) ;
	var queryString = "../ajax/mcacompanies.php";
	ajaxobjext36 = $.ajax(
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
				outputselect.html(response);
					if(selectid && comparevalue)
						autoselect(selectid,comparevalue);
			}
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});		
	$('#regiondiv').html('<select name="form_region" id="form_region"><option value = "">- - - -Select a District First - - - -</option></select>') ;
}


function saveadditionaldetails()
{
	var msg_box = $("#errormessage");
	if($("#lastslno").val() == '')
	{
		 $("#errormessage").html(errormessage('Please select a company from the list'));
		return false;
	}
	else
	{
	  
	  var field = $("#contactperson");  
	  if (!field.val())
	  { msg_box.html(errormessage("Please Enter the Name.")); field.focus(); return false;}
	  var field = $("#address");  
	  if (!field.val())
	  { msg_box.html(errormessage("Please Enter the Address.")); field.focus(); return false;}
	  var field = $("#emailid");  
	  if (!field.val())
	  { msg_box.html(errormessage("Please Enter the email ID.")) ; field.focus(); return false;}
	  if(checkemail(field.val()) == false)
	  { msg_box.html(errormessage("Please Enter the Valid email ID.")); field.focus(); return false;}
	  var field = $("#state");
	  if (!field.val())
	  { msg_box.html(errormessage("Please Select a State.")); field.focus(); return false;}
	  var field = $("#form_district");  
	  if (!field.val())
	  { msg_box.html(errormessage("Please Select a District.")); field.focus(); return false;}
	  var field = $("#place");  
	  if (!field.val())
	  { msg_box.html(errormessage("Please Enter the Place.")); field.focus(); return false;}
	  var field = $("#cell");  
	  if (!field.val())
	  { msg_box.html(errormessage("Please Enter the cell Number.")); field.focus(); return false;}
	  if(field.val()) { if(!validatecell(field.val())) { msg_box.html(errormessage('Please Enter the valid Cell Number.')) ; field.focus(); return false; } }
	  var field = $("#phone");  
	  //if (!field.val())
	 // { msg_box.html(errormessage("Please Enter the Phone Number.")); field.focus(); return false;}
	  if(field.val()) { if(!validatephone(field.val())) { msg_box.html(errormessage('Please Enter a valid Phone Number.')); field.focus(); return false; } }
	  var field = $("#stdcode");  
	  if(field.val()) { if(!validatestdcode(field.val())) { msg_box.html(errormessage('Please Enter a valid STD Code.')); field.focus(); return false; } }
	 // if (!field.val())
	//  { msg_box.html(errormessage("Please Enter the STD code.")); field.focus(); return false;}
	   var passData = "switchtype=saveadditionaldetails&contactperson=" + encodeURIComponent($("#contactperson").val()) + "&address=" + encodeURIComponent($("#address").val())  + "&emailid=" +encodeURIComponent($("#emailid").val())+ "&state=" +encodeURIComponent($("#state").val()) + "&district=" +encodeURIComponent($("#form_district").val())+ "&phone=" +encodeURIComponent($("#phone").val())+ "&cell=" +encodeURIComponent($("#cell").val()) + "&stdcode=" +encodeURIComponent($("#stdcode").val())+ "&place=" +encodeURIComponent($("#place").val())+ "&lastslno=" +encodeURIComponent($("#lastslno").val()) + "&addlastslno=" +encodeURIComponent($("#addlastslno").val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
		queryString = "../ajax/mcacompanies.php";
		ajaxcall233 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
		  
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(ajaxresponse['errorcode'] == 1)
				{
					$("#errormessage").html(successmessage(ajaxresponse['errormessage']));
					$('#converttoleadbtn').attr("disabled", false);
					$("#msgboxgreen").html('');
					$("#short_url").html($("#form_companyname").html() +"\r\n"+ $("#contactperson").val() +"\r\n"+ $("#address").val()+"\n"+ $("#place").val()+"\n"+ $("#form_district option:selected").text()+"\n"+ $("#state option:selected").text() +"\n" + $("#phone").val()+"\n"+ $("#cell").val()+"\n"+ $("#emailid").val());
					//$("#short_url").html('hi');
				}
				else
				{
					 $("#errormessage").html(errormessage('Unable to Connect'));
				}
  
			}, 
			error: function(a,b)
			{
				$("#errormessage").html(scripterror());
			}
		});	
	}

}

function resetsaveform()
{
	$("#saveform" )[0].reset();
}


function districtselect(selectid,comparevalue)
{
	var state = $('#state').val();
	var districtdiv = $('#districtdiv');
	var queryString = "../ajax/mcacompanies.php";
	var passdata = "&switchtype=state&statecode=" + state +"&dummy=" + Math.floor(Math.random()*100032680100);
	ajaxobjext17 = $.ajax(
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
				districtdiv.html(response);
				if(selectid && comparevalue)
					autoselect(selectid,comparevalue);	
			}
			
		}, 
		error: function(a,b)
		{
			districtdiv.html(scripterror());
		}
	});		
}


function converttolead()
{
	if($("#lastslno" ).val() == '')
		return false;
	else
	{
	  $("#submitform" )[0].reset();
	  $("#msg_box" ).html('');
	  $('#form_dealer').hide();
	  $("#displayname" ).html($("#contactperson" ).val());
	  $("#displayaddress" ).html($("#address" ).val());
	  $("#displayemailid" ).html($("#emailid" ).val());
	  $("#displaystate" ).html($("#state option:selected").text());
	  $("#displaydistrict" ).html($("#form_district option:selected").text());
	  $("#displayplace" ).html($("#place" ).val());
	  $("#displaycell" ).html($("#cell" ).val());
	  $("#displayphone" ).html($("#phone" ).val());
	  $("#displaystdcode" ).html($("#stdcode" ).val());
	  
	  $("").colorbox({ inline:true, href:"#inline_example1", onLoad: function() { $('#cboxClose').hide()}});
	}

}


function checkaspermapping()
{
	if($('#aspermapping:checked').val() == 'on')
	{
		$('#form_dealer').attr('disabled',true) ;
		$('#form_dealer').hide();
		$('#help').hide();
	}
	else
	{
		$('#form_dealer').attr('disabled',false) ;
		$('#form_dealer').show();
	}
}

function uploadleadvalues()
{
	var form = $("#submitform");
	var msg_box = $("#msg_box");
	var field = $("#form_product");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select the Product Name.")); field.focus(); return false;}
	var field = $("#form_source");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select the Source of Lead.")); field.focus(); return false;}
	var field = $("#form_leadremarks");  
	if (!field.val())
	{ msg_box.html(errormessage("Please enter the Remarks.")); field.focus(); return false;}
	if($("#aspermapping:checked").val() != 'on')
	{
		var field = $("#form_dealer");  
		if (!field.val())
		{ msg_box.html(errormessage("Please Select the Dealer name.")); field.focus(); return false;}
	}
	
	var dealervalue; 
	if($("#aspermapping:checked").val() == 'on')
	{
		dealervalue = "mapping";
	}
	else
	{
		dealervalue = $("#form_dealer").val();
	}
	msg_box.html(processing());
	var passdata = "&switchtype=uploadlead&form_companyname=" + encodeURIComponent($("#form_companyname").html()) + "&form_name=" + encodeURIComponent($("#contactperson").val()) + "&form_address=" + encodeURIComponent($("#address").val())  + "&form_place=" + encodeURIComponent($("#place").val()) + "&form_phone=" + encodeURIComponent($("#phone").val())+ "&form_cell=" + encodeURIComponent($("#cell").val())+ "&form_stdcode=" + encodeURIComponent('000') + "&state=" + encodeURIComponent($("#state").val())+ "&district=" + encodeURIComponent($("#form_district").val())+ "&form_email=" + encodeURIComponent($("#emailid").val()) + "&form_product=" + encodeURIComponent($("#form_product").val()) + "&form_source=" + encodeURIComponent($("#form_source").val()) + "&form_dealer=" + encodeURIComponent(dealervalue) + "&form_leadremarks=" + encodeURIComponent($("#form_leadremarks").val())+ "&lastslno=" + encodeURIComponent($("#lastslno").val()) +"&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
	var queryString = "../ajax/mcacompanies.php";
	ajaxobjext38 = $.ajax(
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
				var ajaxresponse = response.split('^');// alert(ajaxcal033.responseText)
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1]));
					$("#submitform" )[0].reset();
					$('#form_dealer').hide();
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1]));
				}
				else
				{
					msg_box.html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
		}
	});	
}


function hideshowdirectorinfodiv()
{
	if($('#directorinfo').is(':visible'))
	{
		$('#directorinfo').hide();
		$('#toggleimg2').attr('src',"../images/plus.jpg");
	}
	else
	{
		$('#directorinfo').show();
		$('#toggleimg2').attr('src',"../images/minus.jpg");
	}
}