// JavaScript Document

function perform(formaction)
{
	if(formaction == 'logout')
	{
		$("#contactdetails").attr('action','../logout.php');
	}
	else if(formaction == 'later')
	{
		$("#contactdetails").attr('action','index.php');
	}
	else if(formaction == 'update')
	{
		var form = $("#contactdetails");
		var cell = $("#cell");
		var emailid = $("#emailid");
		var message = $("#message");
		var name = $("#name");
		if(!name.val())
		{
			message.html(errormessage('Please Enter Name.'));
			name.focus();
			return false;
		}
		else if(!cell.val())
		{
			$("#message").html('');
			cell.focus();
			return false;
		}
		else if(!validatecell(cell.val()))
		{
			message.html(errormessage('Please Enter Valid Cell Number.'));
			cell.focus();
			return false;
		}
		else if (!emailid.val())
		{ 
			message.html(errormessage("Please Enter the email ID."));
			emailid.focus(); 
			return false;
		}
		else if(checkemail(emailid.val()) == false)
		{ 
			message.html(errormessage("Please Enter Valid email ID.")); 
			emailid.focus(); 
			return false;
		}
		var passdata = "&submittype=save&cell="+ cell.val()+"&emailid="+emailid.val()+"&name="+name.val()+"&dummy="+Math.floor(Math.random()*1000782200000);
		var querystring = "../ajax/confirmation.php";
		ajaxobjext27 = $.ajax(
		{
			type: "POST",url: querystring, data: passdata, cache: false,
			success: function(response,status)
			{	
				message.html('');
				var ajaxresponse = response.split('^');//alert(ajaxresponse)
				if(ajaxresponse[0] == 1)
				{
					message.html(successmessage(ajaxresponse[1]));
					window.location = 'index.php';
				}
				else 
				{
					message.html(scripterror());
				}
			}, 
			error: function(a,b)
			{
				message.html('');
				message.html(scripterror());
			}
		});		
	}
}