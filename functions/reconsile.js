// JavaScript Document

var totalleadcount;
var totallooprun;
var currentlooprun;
var totalreconsiled;


function reconsilenow()
{
	totalleadcount = 0;
	totallooprun = 0;
	currentlooprun = 0;
	totalreconsiled = 0;
	var form =$('#reconsileform'); 
	var reconsiletype = $('input[name=unmappedleads]:checked').val();
	if(!reconsiletype)
	{
		$("#reconsileresult").html(errormessage('Please click on any of the required radio button to reconsile.'));
		return false;
	}
	$('#reconsile').attr('disabled',true);

	//Get the count of leads to be reconsiled 
	var passdata = "&submittype=getcount&reconsiletype=" + reconsiletype +"&dummy=" + Math.floor(Math.random()*100032680100);	
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	ajaxobjext34 = $.ajax(
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
				var ajaxresponse1 = response.split('^'); //alert(ajaxresponse);
				if(ajaxresponse1[0] == '1')
				{
					totalleadcount = ajaxresponse1[1];
					totallooprun = ajaxresponse1[2];
					progressbar();
					reconsileloop(reconsiletype);
				}
				else if(ajaxresponse1[0] == '2')
				{
					$("#reconsileresult").html(errormessage(ajaxresponse1[1]));
					return false;
				}
				else
				{
					$("#reconsileresult").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#reconsileresult").html(scripterror());
		}
	});		
	return status;
}

function reconsileloop(reconsiletype)
{
	currentlooprun++;
	var passdata = "&submittype=reconsileleads&reconsiletype=" + reconsiletype +"&dummy="+ Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/reconsile.php";//alert(passdata);
	ajaxobjext35 = $.ajax(
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
				var ajaxresponse = response.split('^');
				if(ajaxresponse[0] == 1)
				{
					if(currentlooprun < totallooprun)
					{
						totalreconsiled = (totalreconsiled * 1) + (ajaxresponse[1] * 1); //alert(totalcount);
						reconsileloop(reconsiletype);
						progressbar();
					}
					else
					{
						totalreconsiled = (totalreconsiled * 1) + (ajaxresponse[1] * 1);
						var message = totalreconsiled +' '+ 'leads reconsiled as per new mapping available.';
						$("#reconsileresult").html(successmessage(message));
					}
				}
				else
				{
					$("#reconsileresult").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#reconsileresult").html(scripterror());
		}
	});		
}


function progressbar()
{
	$("#progressbar").show();
	$("#abort").show();
	percentage = Math.round((currentlooprun/totallooprun)*100);
	$("#progressbar-in").width(percentage + '%');
	currentprocessed = (currentlooprun * 100 < totalleadcount)?(currentlooprun * 100):totalleadcount;
	$("#progressbar-data").html(currentprocessed + "/" + totalleadcount);
}

function abortreconsileajaxprocess()
{
	ajaxobjext35.abort();
	var message = totalreconsiled +' '+ 'leads reconsiled as per new mapping available.';
	$("#reconsileresult").html(successmessage(message));
	$("#abort").hide();
	$("#progressbar").hide();
}
