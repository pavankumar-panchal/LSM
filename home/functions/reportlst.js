// JavaScript Document

function getdata()
{
	var form = document.filterform;
	var msg_box = document.getElementById("msg_box");

	var field = form.fromdate;
	if (!field.value)
	{ msg_box.innerHTML = errormessage("Please Enter 'From date'."); field.focus(); return false;}
	if(checkdate(field.value) == false)
	{ msg_box.innerHTML = errormessage("Enter a valid 'From date' [dd-mm-yyyy]."); field.focus(); return false;}
	var field = form.todate;
	if (!field.value)
	{ msg_box.innerHTML = errormessage("Please Enter 'To Date'."); field.focus(); return false;}
	if(checkdate(field.value) == false)
	{ msg_box.innerHTML = errormessage("Enter a valid 'To date' [dd-mm-yyyy]."); field.focus(); return false;}


	document.getElementById("gridprocess").innerHTML = processing();
	document.getElementById("msg_box").innerHTML = '';
	var passdata = "&submittype=getdata&fromdate=" + encodeURIComponent(form.fromdate.value) + "&todate=" + encodeURIComponent(form.todate.value) + "&dealerid=" + encodeURIComponent(form.dealerid.value) + "&productid=" + encodeURIComponent(form.productid.value)+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reportlst.php";
	var ajaxcal051 = createajax();
	/*var queryString = "../ajax/reportlst.php?dummy=" + Math.floor(Math.random()*100032680100) + "&reqtype=getdata&fromdate=" + encodeURIComponent(form.fromdate.value) + "&todate=" + encodeURIComponent(form.todate.value) + "&dealerid=" + encodeURIComponent(form.dealerid.value) + "&productid=" + encodeURIComponent(form.productid.value);*/
	ajaxcal051.open("POST", queryString, true);
	ajaxcal051.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal051.onreadystatechange = function()
	{
		if(ajaxcal051.readyState == 4)
		{
			if(ajaxcal051.status == 200)
			{
				var ajaxresponse1 = ajaxcal051.responseText;
				if(ajaxresponse1 == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response1 = ajaxresponse1.split("|^|");
					if(response1[0] == '1')
					{
						document.getElementById("spp1").innerHTML = response1[2];
						document.getElementById("spp2").innerHTML = response1[3];
						document.getElementById("spp3").innerHTML = response1[4];
						document.getElementById("sto1").innerHTML = response1[5];
						document.getElementById("sto2").innerHTML = response1[6];
						document.getElementById("sto3").innerHTML = response1[7];
						document.getElementById("others1").innerHTML = response1[8];
						document.getElementById("others2").innerHTML = response1[9];
						document.getElementById("others3").innerHTML = response1[10];
						document.getElementById("total1").innerHTML = response1[11];
						document.getElementById("total2").innerHTML = response1[12];
						document.getElementById("total3").innerHTML = response1[13];
						document.getElementById("gridprocess").innerHTML = ' For ' + response1[1];
					}
					else
					{
						document.getElementById("gridprocess").innerHTML = scripterror();
					}
				}
			}
			else
			{
				document.getElementById("gridprocess").innerHTML = scripterror();
			}
		}
	}
	ajaxcal051.send(passdata);
}