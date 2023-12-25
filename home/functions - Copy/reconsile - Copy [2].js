// JavaScript Document

var totalleadcount;
var totallooprun;
var currentlooprun;
var totalreconsiled;
var widthtoadd = 0;


function reconsilenow()
{
	totalleadcount = 0;
	totallooprun = 0;
	currentlooprun = 0;
	totalreconsiled = 0;
	var form = document.getElementById('reconsileform');
	var radiovalue = getradiovalue(form.unmappedleads);// alert(radiovalue);
	if(!radiovalue)
	{
		document.getElementById("reconsileresult").innerHTML = errormessage('Please click on any of the required radio button to reconsile.');
		return false;
	}
	document.getElementById("reconsile").disabled = true; 
	//document.getElementById("result").innerHTML = processing();	
	if(radiovalue == 'disableddealers') 
	{
		reconsiledisabled();
	}
	else  if(radiovalue == 'unmappedleads')
	{
		reconsileunmapped();
	}
}

function reconsiledisabled()
{
	//Get the count of leads to be reconsiled 
	var passdata = "&submittype=disabledcount&dummy=" + Math.floor(Math.random()*100032680100);	
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	//reconsileprocessimage();
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
					totalleadcount = ajaxresponse1[1];
					totallooprun = ajaxresponse1[2];
					document.getElementById("totalleadcount").value = totalleadcount;
					document.getElementById("totallooprun").value = totallooprun;
					reconsileloopdisabled();
				}
				else if(ajaxresponse1[0] == '2')
				{
					document.getElementById("reconsileresult").innerHTML = errormessage(ajaxresponse1[1]);
				 	return false;
				}
				else
				{
					document.getElementById("reconsileresult").innerHTML = scripterror();
				}
			}
			else
			{
				document.getElementById("reconsileresult").innerHTML = scripterror();
			}
		}
	}
	ajaxcal031.send(passdata);
	//return status;
}

function reconsileloopdisabled()
{
	currentlooprun++;
	document.getElementById('divimage').style.display = 'block';
	reconsileprocessimage();
	document.getElementById("currentlooprun").value = currentlooprun;
	var passdata = "&submittype=disableddealers&unmappedleads=disableddealers&dummy="+ Math.floor(Math.random()*100032680100);
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
				var ajaxresponse = ajaxcal030.responseText.split('^');
				if(ajaxresponse[0] == 1)
				{
					if(currentlooprun < totallooprun)
					{
						totalreconsiled = (totalreconsiled * 1) + (ajaxresponse[1] * 1); //alert(totalcount);
						reconsileloopdisabled();
						var displaymessage = totalreconsiled +' '+ 'of' + ' ' +totalleadcount;
						document.getElementById("disp").innerHTML = displaymessage;
					}
					else
					{
						totalreconsiled = (totalreconsiled * 1) + (ajaxresponse[1] * 1);
						document.getElementById('divimage').style.display = 'none';
						var message = totalreconsiled +' '+ 'leads reconsiled as per new mapping available.';
						document.getElementById("reconsileresult").innerHTML = successmessage(message);
					}
				}
				else
				{
					document.getElementById("reconsileresult").innerHTML = scripterror();
				}
			}
			else
			{
				document.getElementById("reconsileresult").innerHTML = scripterror();
			}
		}
	}
	ajaxcal030.send(passdata);
}


function reconsileunmapped()
{
	//Get the count of leads to be reconsiled
	var passdata = "&submittype=unmappedcount&dummy=" + Math.floor(Math.random()*100032680100);	
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	document.getElementById('divimage').style.display = 'block';
	reconsileprocessimage();
	var ajaxcal032 = createajax();
	ajaxcal032.open("POST", queryString, true);
	ajaxcal032.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal032.onreadystatechange = function()
	{
		if(ajaxcal032.readyState == 4)
		{
			if(ajaxcal032.status == 200)
			{
				var ajaxresponse1 = ajaxcal032.responseText.split('^'); 
				if(ajaxresponse1[0] == '1')
				{
					totalleadcount = ajaxresponse1[1];
					totallooprun = ajaxresponse1[2];
					document.getElementById("totalleadcount").value = totalleadcount;
					document.getElementById("totallooprun").value = totallooprun;
					reconsileloopunmapped();
				}
				else if(ajaxresponse1[0] == '2')
				{
					document.getElementById("reconsileresult").innerHTML = errormessage(ajaxresponse1[1]);
				 	return false;
				}
				else
				{
					document.getElementById("reconsileresult").innerHTML = scripterror();
				}
			}
			else
			{
				document.getElementById("reconsileresult").innerHTML = scripterror();
			}
		}
	}
	ajaxcal032.send(passdata);
}

function reconsileloopunmapped()
{
	currentlooprun++;
	reconsileprocessimage();
	document.getElementById("currentlooprun").value = currentlooprun;
	var passdata = "&submittype=unmappedleads&dummy="+ Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	var ajaxcal033 = createajax();
	ajaxcal033.open("POST", queryString, true);
	ajaxcal033.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal033.onreadystatechange = function()
	{
		if(ajaxcal033.readyState == 4)
		{
			if(ajaxcal033.status == 200)
			{
				var ajaxresponse = ajaxcal033.responseText.split('^');
				if(ajaxresponse[0] == 1)
				{
							if(currentlooprun < totallooprun)
							{
								totalreconsiled = (totalreconsiled * 1) + (ajaxresponse[1] * 1); //alert(totalcount);
								reconsileloopunmapped();
								var displaymessage = totalreconsiled +' '+ 'of' + ' ' +totalleadcount;
								document.getElementById("disp").innerHTML = displaymessage;
							}
							else
							{
								totalreconsiled = (totalreconsiled * 1) + (ajaxresponse[1] * 1);
								document.getElementById('divimage').style.display = 'none';
								var message = totalreconsiled +' '+ 'leads reconsiled as per new mapping available.';
								document.getElementById("reconsileresult").innerHTML = successmessage(message);
							}
				}
				else
				{
					document.getElementById("reconsileresult").innerHTML = scripterror();
				}
			}
			else
			{
				document.getElementById("reconsileresult").innerHTML = scripterror();
			}
		}
	}
	ajaxcal033.send(passdata);
}

function reconsileprocessimage()
{
	alert(currentlooprun);
	
	if(currentlooprun == 1)
	{
		if(totallooprun == 0)
		{
			var outerdivwidth = document.getElementById("divimage").style.width ;
			document.getElementById("innerdivimage").style.width = outerdivwidth;
			document.getElementById("innerdivimage").style.background = '#acd037';
		}
		else
		{
			document.getElementById("innerdivimage").style.width = '0px';
			document.getElementById("divimage").style.width = '150px'; 
			document.getElementById("divimage").style.background = '#fefefc';
			var curwidth = document.getElementById("divimage").style.width;
			curwidth = parseInt(curwidth);
			widthtoadd =  Math.round(curwidth / totallooprun);		
			document.getElementById("innerdivimage").style.width = widthtoadd + "px";
		}
	}
	else
	{
		
		 if(currentlooprun == totallooprun - 1)
		{
			//alert('here');
			var outerdivwidth = document.getElementById("divimage").style.width ;  //alert(outerdivwidth + 'ow')
			var innerdivwidth = document.getElementById("innerdivimage").style.width; //alert(innerdivwidth + 'iw')
			var finalwidth = parseInt(outerdivwidth) - parseInt(innerdivwidth); //alert(finalwidth + 'FW');
			innerdivwidth = finalwidth + parseInt(innerdivwidth) - widthtoadd + "px";  //alert(innerdivwidth + 'tot');
			document.getElementById("innerdivimage").style.width = innerdivwidth;
			document.getElementById("innerdivimage").style.background = '#acd037';
			
		}
		else
		{
			var innerdivwidth = document.getElementById("innerdivimage").style.width;
			innerdivwidth = parseInt(innerdivwidth) + widthtoadd + "px" ;
			document.getElementById("innerdivimage").style.width = innerdivwidth;
			document.getElementById("innerdivimage").style.background = '#acd037';
		}	
	}	
}



