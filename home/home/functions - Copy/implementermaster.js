// JavaScript Document

function griddata()
{
	ajax = createajax();
	var queryString = "../ajax/implementermaster.php?dummy=" + Math.floor(Math.random()*100032680100) + "&reqtype=griddata";
	ajax.open("GET", queryString, true);
	ajax.onreadystatechange = function()
	{
		if(ajax.readyState == 4)
		{
			var ajaxresponse = ajax.responseText;
			document.getElementById("callgrid").innerHTML = ajaxresponse;
		}
	}
	ajax.send(null);
}


function formsubmit(command)
{
	var form = document.implementerform;
	var msg_box = document.getElementById("msg_box");

	var field = form.form_name;  
	if (!field.value)
	{ msg_box.innerHTML = "Please Enter a Name."; field.focus(); return false;}
	var field = form.form_location;  
	if (!field.value)
	{ msg_box.innerHTML = "Please Enter the Location."; field.focus(); return false;}
	var field = form.form_email;  
	if (!field.value)
	{ msg_box.innerHTML = "Please Enter the email ID."; field.focus(); return false;}
	if(checkemail(field.value) == false)
	{ msg_box.innerHTML = "Please Enter Valid email ID."; field.focus(); return false;}
	var field = form.form_cell;  
	if (!field.value)
	{ msg_box.innerHTML = "Please Enter Cell Number."; field.focus(); return false;}
	var field = form.form_cell;  
	if (!validatecell(field.value))
	{ msg_box.innerHTML = "Please Enter Valid Cell Number."; field.focus(); return false;}
	var field = form.form_username;  
	if (!field.value)
	{ msg_box.innerHTML = "Please Enter the username."; field.focus(); return false;}
	var field = form.form_password;  
	if (!field.value)
	{ msg_box.innerHTML = "Please Enter the password."; field.focus(); return false;}

	msg_box.innerHTML = processing();
	
	if(command == 'save')
	{
		queryString = "../ajax/implementermaster.php?dummy=" + Math.floor(Math.random()*10230000000) + "&reqtype=save&form_recid=" + form.form_recid.value + "&form_name=" + form.form_name.value + "&form_location=" + form.form_location.value + "&form_email=" + form.form_email.value + "&form_cell=" + form.form_cell.value + "&form_username=" + form.form_username.value + "&form_password=" + form.form_password.value;

	}
	else if(command == 'delete')
	{
		queryString = "../ajax/implementermaster.php?dummy=" + Math.floor(Math.random()*10230000000) + "&reqtype=delete&form_recid=" + form.form_recid.value;
	}

	ajax = 	createajax();
	ajax.open("GET", queryString, true);
		ajax.onreadystatechange = function()
		{
			if(ajax.readyState == 4)
			{
				var ajaxresponse = ajax.responseText;
				msg_box.innerHTML = ajaxresponse;
				griddata();
				newentry();
			}
		}
	ajax.send(null);
}

function newentry()
{
//	document.getElementById("msg_box").innerHTML = '';
	document.getElementById("form_recid").value = '';
	document.implementerform.reset();
	enablesave();
	disabledelete();
}

//Function to enable the delete button [Common Function]
function enabledelete()
{
	if(document.getElementById('delete'))
	document.getElementById('delete').disabled = false;
}

//Function to enable the save button [Common Function]
function enablesave()
{
	if(document.getElementById('save'))
	document.getElementById('save').disabled = false;
}

//Function to disable the save button [Common Function]
function disablesave()
{
	if(document.getElementById('save'))
	document.getElementById('save').disabled = true;
}

//Function to disable the delete button [Common Function]
function disabledelete()
{
	if(document.getElementById('delete'))
	document.getElementById('delete').disabled = true;
}


function gridtoform(id)
{
		ajax = createajax();
		var queryString = "../ajax/implementermaster.php?dummy=" + Math.floor(Math.random()*100032680100) + "&reqtype=gridtoform&form_recid=" + id;
		ajax.open("GET", queryString, true);
		ajax.onreadystatechange = function()
		{
			if(ajax.readyState == 4)
			{
				var ajaxresponse = ajax.responseText;
				var response = ajaxresponse.split("^");
				document.getElementById('form_recid').value = response[0];
				document.getElementById('form_name').value = response[1];
				document.getElementById('form_location').value = response[2];
				document.getElementById('form_email').value = response[3];
				document.getElementById('form_cell').value = response[4];
				document.getElementById('form_username').value = response[5];
				document.getElementById('form_password').value = response[6];
				document.getElementById('hiddenpwd').innerHTML = response[6];
				document.getElementById('msg_box').innerHTML = '';
				enabledelete();
				enablesave();
			}
		}
		ajax.send(null); 
}
