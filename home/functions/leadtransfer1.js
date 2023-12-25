// JavaScript Document

function griddata(startlimit)
{
	document.getElementById("gridprocess").innerHTML = processing();
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100); 
	var queryString = "../ajax/leadtransfer1.php";
	var ajaxcal033 = createajax();
	//var queryString = "../ajax/leadtransfer1.php?dummy=" + Math.floor(Math.random()*100032680100) + "&reqtype=griddata";
	ajaxcal033.open("POST", queryString, true);
	ajaxcal033.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal033.onreadystatechange = function()
	{
		if(ajaxcal033.readyState == 4)
		{
			if(ajaxcal033.status == 200)
			{
				var ajaxresponse = ajaxcal033.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{ //alert(ajaxresponse)
					var response = ajaxresponse.split("|^|");
					if(response[0] == '1')
					{
						document.getElementById("tabgroupgridc1_1").innerHTML = response[1];
						document.getElementById("getmorelink").innerHTML = response[2];
						document.getElementById("gridprocess").innerHTML = ' => [Leads of Last 2 Days (' + response[3] +' Records)]';
					}
					else if(response[0] == '2')
					{
						document.getElementById("tabgroupgridc1_1").innerHTML = response[1];
						document.getElementById("getmorelink").innerHTML = response[2];
						document.getElementById("gridprocess").innerHTML = ' => [Leads of Last 2 Days (' + response[3] +' Records)]';
					}
					else
					{
						document.getElementById("gridprocess").innerHTML = scripterror1();
					}
				}
			}
			else
			{
				document.getElementById("gridprocess").innerHTML = scripterror1();
			}
		}
	}
	ajaxcal033.send(passdata);
}

function filtering()
{
	
	var form = document.filter;
	var textfield = form.searchcriteria.value;
	var subselection = getradiovalue(form.databasefield);
	var datatype = getradiovalue(form.datatype);
	form.srchhiddenfield.value = textfield;
	form.subselhiddenfield.value = subselection;
	form.datatypehiddenfield.value = datatype;
	if(textfield.length > 0)
	{
		document.getElementById("gridprocess").innerHTML = processing();
		var passdata ="&submittype=filter&searchtext=" + textfield + "&subselection=" + subselection + "&datatype=" + datatype+"&dummy=" + Math.floor(Math.random()*10230000000);//alert(passdata)
		var queryString = "../ajax/leadtransfer1.php";
		var ajaxcal036 = createajax();
		ajaxcal036.open("POST", queryString, true);
		ajaxcal036.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcal036.onreadystatechange = function()
		{
			if(ajaxcal036.readyState == 4)
			{
				if(ajaxcal036.status == 200)
				{
					var ajaxresponse = ajaxcal036.responseText;//alert(ajaxresponse)
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split("|^|");
						if(response[0] == '1')
						{
							document.getElementById("tabgroupgridc1_1").innerHTML = response[1];
							document.getElementById("getmorelink").innerHTML = response[2];
							document.getElementById("gridprocess").innerHTML = ' => Filter Applied (' + response[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]';
						}
						else
						{
							document.getElementById("gridprocess").innerHTML = scripterror1();
						}
					}
				}
				else
				{
					document.getElementById("gridprocess").innerHTML = scripterror1();
				}
			}
		}
		ajaxcal036.send(passdata);
	}
	else
	{
		//alert('Please enter text to proceed for further searching.!');
		document.getElementById('searchbox').innerHTML = errormessage("Please enter the text to proceed for further searching.!"); 
		form.searchcriteria.focus();
	}
}


function gridtoform(id)
{
	document.getElementById("gridprocess").innerHTML = processing();
	var passdata ="&submittype=gridtoform&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);//alert(passdata)
	var queryString = "../ajax/leadtransfer1.php";
	var ajaxcal034 = createajax();
	ajaxcal034.open("POST", queryString, true);
	ajaxcal034.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal034.onreadystatechange = function()
	{
		if(ajaxcal034.readyState == 4)
		{
			if(ajaxcal034.status == 200)
			{
				document.getElementById("gridprocess").innerHTML = '';
				var ajaxresponse = ajaxcal034.responseText; //alert(ajaxresponse)
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split("|^|");
					if(response[0] == '1')
					{
						document.getElementById('form_recid').value = response[1];
						document.getElementById('leadisplay').innerHTML = response[2];
						document.getElementById('productdisplay').innerHTML = response[3];
						document.getElementById('fromdealerdisplay').innerHTML = response[4];
						document.getElementById('msg_box').innerHTML = "";
					}
					else
					{
						document.getElementById('msg_box').innerHTML = scripterror();
					}
				}
			}
			else
			{
				document.getElementById('msg_box').innerHTML = scripterror();
			}
		}
	}
	ajaxcal034.send(passdata); 
}

function formsubmit(command)
{
	var form_recid = document.getElementById("form_recid");
	var form_dealer = document.getElementById("form_dealer");
	var msg_box = document.getElementById("msg_box");

	if (!form_recid.value)
	{ msg_box.innerHTML = errormessage("Please Select a Lead to transfer."); form_recid.focus(); return false;}
	if (!form_dealer.value)
	{ msg_box.innerHTML = errormessage("Please Select Dealer, for whom the lead needs to be transferred."); form_dealer.focus(); return false;}
	
	msg_box.innerHTML = processing();
	var passdata = "&submittype=save&form_recid=" + form_recid.value + "&form_dealer=" + form_dealer.value +"&dummy=" + Math.floor(Math.random()*10230000000);//alert(passdata);
	var queryString = "../ajax/leadtransfer1.php";
	var ajaxcal035 = createajax();
	ajaxcal035.open("POST", queryString, true);
	ajaxcal035.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal035.onreadystatechange = function()
	{
		if(ajaxcal035.readyState == 4)
		{
			if(ajaxcal035.status == 200)
			{
				var ajaxresponse = ajaxcal035.responseText.split('^');//alert(ajaxresponse)
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					if(ajaxresponse[0] == '1')
					{
						msg_box.innerHTML = successmessage(ajaxresponse[1]);
					}
					else if(ajaxresponse[0] == '2')
					{
						msg_box.innerHTML = errormessage(ajaxresponse[1]);
					}
					else
					{
						msg_box.innerHTML = scripterror();
					}
				}
			}
			else
			{
				msg_box.innerHTML = scripterror();
			}
		}
	}
	ajaxcal035.send(passdata);
}

function getmorerecords(startlimit,slnocount,showtype,type)
{
	document.getElementById('getmorelink').innerHTML = processing();
	var form = document.filter;
	var textfield = form.srchhiddenfield.value;
	var subselection = form.subselhiddenfield.value;
	var datatype = form.datatypehiddenfield.value;
	if(type == 'transfer')
	{
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'search')
	{
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&searchtext=" + textfield + "&subselection=" + subselection + "&datatype=" + datatype+"&dummy=" + Math.floor(Math.random()*10230000000);//alert(passdata)
	}
	var queryString = "../ajax/leadtransfer1.php";//alert(passdata)
	var ajaxcal104 = createajax();
	ajaxcal104.open("POST", queryString, true);
	ajaxcal104.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal104.onreadystatechange = function()
	{
		if(ajaxcal104.readyState == 4)
		{
			if(ajaxcal104.status == 200)
			{
				var ajaxresponse = ajaxcal104.responseText.split('|^|');//alert(ajaxresponse);
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					if(ajaxresponse[0] == '1')
					{
						document.getElementById('resultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
						document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('resultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ ajaxresponse[1] ;
						document.getElementById('getmorelink').innerHTML =  ajaxresponse[2];					
					}
					else if(ajaxresponse[0] == '1')
					{
						document.getElementById('resultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
						document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('resultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ ajaxresponse[1] ;
						document.getElementById('getmorelink').innerHTML =  ajaxresponse[2];					
					}
					else
					{
						document.getElementById("gridprocess").innerHTML = scripterror1();
					}
				}
			}
			else
			{
				document.getElementById("gridprocess").innerHTML = scripterror1();
			}
		}
	}
	ajaxcal104.send(passdata); 
	
}

function clear1()
{
	var form = document.filter;
	form.reset();
	document.getElementById('searchbox').innerHTML = '';
}