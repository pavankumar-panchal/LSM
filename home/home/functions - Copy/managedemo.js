// JavaScript Document

function griddata()
{
	document.getElementById("gridprocess").innerHTML = processing();
	var passdata ="&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/managedemo.php";
	var ajaxcal065 = createajax();
	//var queryString = "../ajax/managedemo.php?dummy=" + Math.floor(Math.random()*100032680100) + "&reqtype=griddata";
	ajaxcal065.open("POST", queryString, true);
	ajaxcal065.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal065.onreadystatechange = function()
	{
		if(ajaxcal065.readyState == 4)
		{
			if(ajaxcal065.status == 200)
			{
				var ajaxresponse = ajaxcal065.responseText;
				var response = ajaxresponse.split("|^|");
				document.getElementById("callgrid").innerHTML = response[0];
				document.getElementById("gridprocess").innerHTML = ' => [Leads of Last 2 Days (' + response[1] +' Records)]';
			}
			else
			{
				document.getElementById("callgrid").innerHTML = scripterror();
			}
		}
	}
	ajaxcal065.send(passdata);
}

function filtering()
{
	var form = document.filter;
	var textfield = form.searchcriteria.value;
	var subselection = getradiovalue(form.databasefield);
	var datatype = getradiovalue(form.datatype);

	if(textfield.length > 2)
	{
		document.getElementById("gridprocess").innerHTML = processing();
		var passdata ="&submittype=filter&searchtext=" + textfield + "&subselection=" + subselection + "&datatype=" + datatype +"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/managedemo.php";
		//queryString = "../ajax/managedemo.php?dummy=" + Math.floor(Math.random()*10230000000) + "&reqtype=filter&searchtext=" + textfield + "&subselection=" + subselection + "&datatype=" + datatype;
		var ajaxcal066 = 	createajax();
		ajaxcal066.open("POST", queryString, true);
		ajaxcal066.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcal066.onreadystatechange = function()
		{
			if(ajaxcal066.readyState == 4)
			{
				if(ajaxcal066.status == 200)
				{
					var ajaxresponse = ajaxcal066.responseText;
					var response = ajaxresponse.split("|^|");
					document.getElementById("callgrid").innerHTML = response[0];
					document.getElementById("gridprocess").innerHTML = ' => Filter Applied (' + response[1] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]';
				}
				else
				{
					document.getElementById("callgrid").innerHTML = scripterror();
				}
			}
		}
		ajaxcal066.send(passdata);
	}
}


function gridtoform(id)
{
	var passdata ="&submittype=gridtoform&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/managedemo.php";
	var ajaxcal067 = createajax();
		//var queryString = "../ajax/managedemo.php?dummy=" + Math.floor(Math.random()*100032680100) + "&reqtype=gridtoform&form_recid=" + id;
	ajaxcal067.open("POST", queryString, true);
	ajaxcal067.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal067.onreadystatechange = function()
	{
		if(ajaxcal067.readyState == 4)
		{
			if(ajaxcal067.status == 200)
			{
				var ajaxresponse = ajaxcal067.responseText;
				var response = ajaxresponse.split("|^|");
				document.getElementById('form_recid').value = response[0];
				document.getElementById('leadisplay').innerHTML = response[1];
			}
			else
			{
				document.getElementById('leadisplay').innerHTML = scripterror();
			}
		}
	}
	ajaxcal067.send(passdata); 
}

function formsubmit(command)
{
	var form_recid = document.getElementById("form_recid");
	var form_dealer = document.getElementById("form_dealer");
	var msg_box = document.getElementById("msg_box");

	if (!form_recid.value)
	{ msg_box.innerHTML = "Please Select a Lead to transfer."; field.focus(); return false;}
	if (!form_dealer.value)
	{ msg_box.innerHTML = "Please Select Dealer, for whom the lead needs to be transferred."; field.focus(); return false;}

	msg_box.innerHTML = processing();
	var passdata ="&submittype=save&form_recid=" + form_recid.value + "&form_dealer=" + form_dealer.value+"&dummy=" + Math.floor(Math.random()*10230000000);
	var queryString = "../ajax/managedemo.php";
	//queryString = "../ajax/managedemo.php?dummy=" + Math.floor(Math.random()*10230000000) + "&reqtype=save&form_recid=" + form_recid.value + "&form_dealer=" + form_dealer.value;
	var ajaxcal068 = createajax();
	ajaxcal068.open("POST", queryString, true);
	ajaxcal068.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal068.onreadystatechange = function()
	{
		if(ajaxcal068.readyState == 4)
		{
			var ajaxresponse = ajaxcal068.responseText;
			msg_box.innerHTML = ajaxresponse;
		}
	}
	ajaxcal068.send(passdata);
}

