// JavaScript Document


function addtologs()
{
	var passdata ="&submittype=runquery&dummy=" + Math.floor(Math.random()*100032680100); alert(passdata);
	var queryString = "../others-test/notviewed1.php";
	var ajaxcal037 = createajax();
	ajaxcal037.open("POST", queryString, true);
	ajaxcal037.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcal037.onreadystatechange = function()
	{
		if(ajaxcal037.readyState == 4)
		{
			if(ajaxcal037.status == 200)
			{
				var ajaxresponse1 = ajaxcal037.responseText.split('^'); alert(ajaxresponse1);
				if(ajaxresponse1[0] == '1')
				{
					alert(ajaxresponse[1]);
				}
			}
			else
			{
				alert('Sorry could not process.');
			}
		}
	}
	ajaxcal037.send(passdata);
}