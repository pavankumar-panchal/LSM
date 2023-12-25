// JavaScript Document
var count = 0;
var totalcount = 0;
function reconsilenow()
{
	//if(count != 0)
		//sleep(5000);
	var form = document.getElementById('reconsileform');
	var radiovalue = getradiovalue(form.unmappedleads);// alert(radiovalue);
	if(!radiovalue)
	{
		document.getElementById("result").innerHTML = errormessage('Please click on any of the required radio button to reconsile.');
		return false;
	}
	else
	{
		if(count == 0 && radiovalue == 'disableddealers') 
		{
			count++;
			getreconsilecount();
		}
		else  if(count == 0 && radiovalue == 'unmappedleads')
		{
			count++;
			getunmappedleadcount();
		}
		document.getElementById("reconsile").disabled = true; 
		document.getElementById("result").innerHTML = processing();	
		var passdata = "&submittype=reconsile&unmappedleads="+radiovalue+"&dummy="+ Math.floor(Math.random()*100032680100);
		var queryString = "../ajax/reconsile.php";//alert(passdata);
		var ajaxcal030 = createajax();
		ajaxcal030.open("POST", queryString, true);
		ajaxcal030.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcal030.onreadystatechange = function()
		{
			if(ajaxcal030.readyState == 4)
			{
				if(ajaxcal030.status == 200)
				{
					var ajaxresponse = ajaxcal030.responseText.split('^');alert(ajaxresponse);
					if(ajaxresponse[0] == 1)
					{
						if(radiovalue == 'disableddealers')
						{
							if(count != document.getElementById("totallooprun").value + 1 && ajaxresponse[1] != '0' &&  ajaxresponse[1] != '')
							{
								reconsilenow();
								totalcount =(totalcount * 1) + (ajaxresponse[1] * 1); //alert(totalcount);
								count++; 
							}
							else
							{
								totalcount = (totalcount * 1) + (ajaxresponse[1] * 1);
								var message = totalcount +' '+ 'leads reconsiled as per new mapping available.';
								if(document.getElementById("result").innerHTML != '')
								document.getElementById("result").innerHTML = successmessage(message);
							}
						}
						else if(radiovalue == 'unmappedleads')
						{
							if(count != document.getElementById("totallooprun1").value + 1 && ajaxresponse[1] != '0')
							{
								reconsilenow();
								totalcount =(totalcount * 1) + (ajaxresponse[1] * 1); //alert(totalcount);
								count++; 
							}
							else
							{
								totalcount =(totalcount * 1) + (ajaxresponse[1] * 1);
								var message = totalcount +' '+ 'leads reconsiled as per new mapping available.';
								document.getElementById("result").innerHTML = successmessage(message);
								//return false;
							}
						}
					}
					else
					{
						document.getElementById("result").innerHTML = scripterror();
					}
				}
				else
				{
					document.getElementById("result").innerHTML = scripterror();
				}
			}
		}
		ajaxcal030.send(passdata);		
	}	
}

function getreconsilecount()
{
	var passdata = "&submittype=getleadcount&dummy=" + Math.floor(Math.random()*100032680100);	
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	var ajaxcal031 = createajax();
	ajaxcal031.open("POST", queryString, true);
	ajaxcal031.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal031.onreadystatechange = function()
	{
		if(ajaxcal031.readyState == 4)
		{
			if(ajaxcal031.status == 200)
			{
				var ajaxresponse1 = ajaxcal031.responseText.split('^'); //alert(ajaxresponse);
				if(ajaxresponse1[0] == '1')
				{
					document.getElementById("totalleadcount").value = ajaxresponse1[1];
					document.getElementById("totallooprun").value = ajaxresponse1[2];
					//status = true;
				}
				else if(ajaxresponse1[0] == '2')
				{
					document.getElementById("result").innerHTML = errormessage(ajaxresponse1[1]);
				 	return false;
				}
				else
				{
					document.getElementById("result").innerHTML = scripterror();
				}
			}
			else
			{
				document.getElementById("result").innerHTML = scripterror();
			}
		}
	}
	ajaxcal031.send(passdata);
	//return status;
}


function getunmappedleadcount()
{
	var passdata = "&submittype=unmappedleadcount&dummy=" + Math.floor(Math.random()*100032680100);	
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	var ajaxcal032 = createajax();
	ajaxcal032.open("POST", queryString, true);
	ajaxcal032.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal032.onreadystatechange = function()
	{
		if(ajaxcal032.readyState == 4)
		{
			if(ajaxcal032.status == 200)
			{
				var ajaxresponse = ajaxcal032.responseText.split('^');
				if(ajaxresponse[0] == '1')
				{
					document.getElementById("totalunmappedleadcount1").value = ajaxresponse[1];
					document.getElementById("totallooprun1").value = ajaxresponse[2];
					//status = true;
				}
				else if(ajaxresponse[0] == '2')
				{
					document.getElementById("result").innerHTML = errormessage(ajaxresponse[1]);
				 	//status = false;
				}
				else
				{
					document.getElementById("result").innerHTML = scripterror();
				}
			}
			else
			{
				document.getElementById("result").innerHTML = scripterror();
			}
		}
	}
	ajaxcal032.send(passdata);
}


function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

