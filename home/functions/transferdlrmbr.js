// JavaScript Document

function griddata(startlimit)
{
		document.getElementById("gridprocess").innerHTML = processing();
		var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
		var queryString = "../ajax/transferdlrmbr.php";
		var ajaxcal054 = createajax();
		ajaxcal054.open("POST", queryString, true);
		ajaxcal054.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcal054.onreadystatechange = function()
		{
			if(ajaxcal054.readyState == 4)
			{
				if(ajaxcal054.status == 200)
				{
					var ajaxresponse = ajaxcal054.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split("|^|"); //alert(response)
						if(response[0] == '1')
						{
							document.getElementById("tabgroupgridc1_1").innerHTML = response[1];
							document.getElementById("getmorelink").innerHTML = response[2];
							document.getElementById("gridprocess").innerHTML = ' => [Leads of Last 2 Days (' + response[3] +' Records)]';
						}
						else if(response[0] == '2')
						{
							document.getElementById("tabgroupgridc1_1").innerHTML = errormessage("No more records found to be displayed.");
						}
					}
				}
				else
				{
					document.getElementById("gridprocess").innerHTML = scripterror1();
				}
			}
		}
		ajaxcal054.send(passdata);
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
		var passdata = "&submittype=filter&searchtext=" + textfield + "&subselection=" + subselection + "&datatype=" + datatype +"&dummy=" + Math.floor(Math.random()*10230000000);//alert(passdata)
		var queryString = "../ajax/transferdlrmbr.php";
		var ajaxcal055 = createajax();
		ajaxcal055.open("POST", queryString, true);
		ajaxcal055.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcal055.onreadystatechange = function()
		{
			if(ajaxcal055.readyState == 4)
			{
				if(ajaxcal055.status == 200)
				{
					var ajaxresponse = ajaxcal055.responseText;//alert(ajaxresponse)
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
							document.getElementById("gridprocess").innerHTML = '';
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
		ajaxcal055.send(passdata);
	}
	else
	{
		document.getElementById('msg_box').innerHTML = errormessage("Please enter the text to proceed for further searching.!");
		form.searchcriteria.focus();
	}
}


function gridtoform(id)
{
	var msg_box = document.getElementById("msg_box");
	document.getElementById("gridprocess").innerHTML = processing();
	msg_box.innerHTML = '';
	var passdata = "&submittype=gridtoform&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/transferdlrmbr.php";
	var ajaxcal056 = createajax();
	ajaxcal056.open("POST", queryString, true);
	ajaxcal056.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal056.onreadystatechange = function()
	{
		if(ajaxcal056.readyState == 4)
		{
			document.getElementById("gridprocess").innerHTML = '';
			if(ajaxcal056.status == 200)
			{
				var ajaxresponse = ajaxcal056.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split("|^|");
					document.getElementById('form_recid').value = response[0];
					document.getElementById('leadisplay').innerHTML = response[1];
					document.getElementById('productdisplay').innerHTML = response[2];
					document.getElementById('fromdlrmbrdisplay').innerHTML = response[3];
				}
			}
			else
			{
				document.getElementById('fromdlrmbrdisplay').innerHTML = scripterror();
			}
		}
	}
	ajaxcal056.send(passdata); 
}

function formsubmit(command)
{
	var form_recid = document.getElementById("form_recid");
	var form_dlrmbr = document.getElementById("form_dlrmbr");
	var msg_box = document.getElementById("msg_box");
	
	if (!form_recid.value)
	{ msg_box.innerHTML = "Please Select a Lead to transfer."; form_recid.focus(); return false;}

	msg_box.innerHTML = processing();
	var passdata = "&submittype=save&form_recid=" + form_recid.value + "&form_dlrmbr=" + form_dlrmbr.value +"&dummy=" + Math.floor(Math.random()*10230000000);
	var queryString = "../ajax/transferdlrmbr.php";	
	var ajaxcal057 = createajax();
	ajaxcal057.open("POST", queryString, true);
	ajaxcal057.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal057.onreadystatechange = function()
	{
		if(ajaxcal057.readyState == 4)
		{
			if(ajaxcal057.status == 200)
			{
				var response = ajaxcal057.responseText;
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
						msg_box.innerHTML = successmessage(ajaxresponse[1]);
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
	ajaxcal057.send(passdata);
}
 

function getmorerecords(startlimit,slnocount,showtype,type)
{
	document.getElementById("gridprocess").innerHTML = processing();
	var form = document.filter;
	var textfield = form.srchhiddenfield.value;
	var subselection = form.subselhiddenfield.value;
	var datatype = form.datatypehiddenfield.value;
	if(type == 'dealer')
	{
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'search')
	{
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&searchtext=" + textfield + "&subselection=" + subselection + "&datatype=" + datatype +"&dummy=" + Math.floor(Math.random()*10230000000);
	}//alert(passdata)
	var queryString = "../ajax/transferdlrmbr.php";	
	var ajaxcal101 = createajax();
	ajaxcal101.open("POST", queryString, true);
	ajaxcal101.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal101.onreadystatechange = function()
	{
		if(ajaxcal101.readyState == 4)
		{
			if(ajaxcal101.status == 200)
			{
				
				var response = ajaxcal101.responseText;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var ajaxresponse =response.split('|^|');//alert(ajaxresponse);
					if(ajaxresponse[0] == '1')
					{
						document.getElementById("gridprocess").innerHTML = '';
						document.getElementById('resultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
						document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('resultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ ajaxresponse[1] ;
						document.getElementById('getmorelink').innerHTML =  ajaxresponse[2];
						document.getElementById("gridprocess").innerHTML = ' => [Leads of Last 2 Days (' + ajaxresponse[3] +' Records)]';
						
					}
					else if(ajaxresponse[0] == '2')
					{
						document.getElementById("gridprocess").innerHTML = '';
						document.getElementById('tabgroupgridc1_1').innerHTML = ajaxresponse[1];
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
	ajaxcal101.send(passdata); 
	
}