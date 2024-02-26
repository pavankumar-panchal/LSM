// JavaScript Document

function changepwd()
{
	var form = $("#passwordform");
	var msg_box = $("#msg_box");
	var field = $("#oldpassword");
	if (!field.val())
	{ msg_box.html(errormessage("Please enter the old password.")); field.focus(); return false;}
	var field = $("#newpassword");
	if (!field.val())
	{ msg_box.html(errormessage("Please enter the new password.")); field.focus(); return false;}
	var field = $("#cnewpassword");
	if (!field.val())
	{ msg_box.html(errormessage("Please enter the confirmation password.")); field.focus(); return false;}

	if ($("#newpassword").val() != $("#cnewpassword").val())
	{ msg_box.html(errormessage("New password and Confirm Password is not matching.")); field.focus(); return false;}

	var passdata = "&submittype=change&oldpassword=" +encodeURIComponent($("#oldpassword").val()) + "&newpassword=" + encodeURIComponent($("#newpassword").val()) + "&cnewpassword=" + encodeURIComponent($("#cnewpassword").val())+"&dummy=" + Math.floor(Math.random()*10230000000);
	var queryString = "../ajax/changepassword-first.php";
	ajaxobjext55 = $.ajax(
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
				var ajaxresponse = response.split('^');//alert(ajaxresponse)
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1]));
					disabl('disfields','change');
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1]));
				}
				else
				{
					msg_box.html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
		}
	});
}

function disabl(hid, su) 
{
	var dis = (su.checked) ? "block" : "none";
	document.getElementById(hid).style.display = dis;
}

function resetform()
{
	$("#passwordform")[0].reset;
	$("#msg_box").html('');
}