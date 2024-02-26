// JavaScript Document

//-----------------------------------------------------------------------------------------------------------------

function processing()
{
return '<img src="../images/aj_loader.gif" width="43" height="11" />';	
}




var rotateMsg = true;
function MsgStatus() 
{
	if(rotateMsg) 
	{
		window.status = '';
		window.defaultStatus = ' Relyon User Login Area';
	}
	if(!rotateMsg) 
	{
		window.status = '';
		window.defaultStatus = ' All rights reserved for Relyon Softech Ltd';
	}
	setTimeout("MsgStatus();rotateMsg=!rotateMsg", 1500);
}
MsgStatus();

//-----------------------------------------------------------------------------------------------------------------

function checkemail(a)
{
  var r1 = new RegExp("(@.*@)|(\\.\\.)|(@\\.)|(^\\.)");
  var r2 = new RegExp("^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$");
  return (!r1.test(a) && r2.test(a));
}

function chkNumeric(objName)
{
	var checkOK = "0123456789";
	var checkStr = objName;
	var allValid = false;
	
	for (i = 0;  i < checkStr.length;  i++)
		{
			ch = checkStr.charAt(i);
			for (j = 0;  j < checkOK.length;  j++)
				{
					if (ch == checkOK.charAt(j) && ch != "," && ch != ".")
						{
							allValid = true;	
							break;
						}
					allValid = false;	
				}
		}
	return allValid;
}

function tog(divid1,obj) 
{
	$('#'+divid1).toggle();
}



function autoselect(selectid,comparevalue)
{
	var selection = document.getElementById(selectid); 
	for(var i = 0; i < selection.length; i++) 
	{
		if(selection[i].value == comparevalue)
		{
			selection[i].selected = "1";
			return;
		}
	}
}

/*function getradiovalue(radioname)
{
	if(radioname.value)
	return radioname.value;
	else
	for(var i = 0; i < radioname.length; i++) 
	{
		if(radioname[i].checked) {
			return radioname[i].value;
		}
	}

}*/

function checkdate(datevalue) //dd-mm-yyyy Eg: 01-04-2008
{
	if(datevalue.length == 10)
	{
		if(isanumber(datevalue.charAt(0)) && isanumber(datevalue.charAt(1)) && isanumber(datevalue.charAt(3)) && isanumber(datevalue.charAt(4)) && isanumber(datevalue.charAt(6)) && isanumber(datevalue.charAt(7)) && isanumber(datevalue.charAt(8)) && isanumber(datevalue.charAt(9)) && datevalue.charAt(2) == '-' && datevalue.charAt(5) == '-')
			return true;
		else
			return false;
	}
	else
		return false;
}

function isanumber(onechar)
{
	if(onechar.charCodeAt(0) >= 48 && onechar.charCodeAt(0) <= 57)
	{
		return true;
	}
	else
		return false;
}

function puttext(inputid, text)
{
	var inputfield = $('#'+inputid);
	if(inputfield.val() == "")
	{
		inputfield.val(text);
	}
}

function cleantext(inputid, text)
{
	var inputfield = $('#'+inputid);
	if(inputfield.val() == text)
	{
		inputfield.val('');
	}
}

function jumpToAnchor(anchorname) 
{
   window.location = String(window.location).replace(/\#.*$/, "") + "#" + anchorname;
}

function compare2dates(smallone,largeone)
{
   var str1  = smallone;
   var str2  = largeone;
   var dt1   = parseInt(str1.substring(0,2),10);
   var mon1  = parseInt(str1.substring(3,5),10);
   var yr1   = parseInt(str1.substring(6,10),10);
   var dt2   = parseInt(str2.substring(0,2),10);
   var mon2  = parseInt(str2.substring(3,5),10);
   var yr2   = parseInt(str2.substring(6,10),10);
   var date1 = new Date(yr1, mon1, dt1);
   var date2 = new Date(yr2, mon2, dt2);

   if(date2 < date1)
      return false;
   else
      return true;
} 

function iscellnumber(fieldvalue)
{
	if((fieldvalue.charAt(0) != 9) && (fieldvalue.charAt(0) != 8) && (fieldvalue.charAt(0) != 7))
		return false;
	else if(fieldvalue.length != 10)
		return false;
	else if(isNaN(fieldvalue))
		return false;
	else
		return true;
}

function validatecell(cellnumber)
{
	var numericExpression = /^[7|8|9]\d{9}(?:(?:([,][\s]|[;][\s]|[,;])[7|8|9]\d{9}))*$/i;
	//var numericExpression = /^((\+)?(\d{2}[-]))?(\d{10})?$/i ;
	if(cellnumber.match(numericExpression)) return true;
	else return false;
}

function validatephone(phonenumber)
{
	var numericExpression = /^([^9]\d{5,9})(?:(?:[,;]([^9]\d{5,9})))*$/i;
	if(phonenumber.match(numericExpression)) return true;
	else return false;
}

function validatestdcode(stdcodenumber)
{
	var numericExpression = /^[0]+[0-9]{2,4}$/i;
	if(stdcodenumber.match(numericExpression)) return true;
	else return false;
}

//Function to display a error message if the script failed
function scripterror()
{
	var msghtml = "<div class='msgboxred'>Unable to Connect....</div>";
	return msghtml;
}

function scripterror1()
{
	var msghtml = "<strong>Unable to Connect....</strong>";
	return msghtml;
}


function successmessage(message)
{
	message = "<div class='msgboxgreen'>"+message+"</div>";
	return message;
}

function errormessage(message)
{
	message = "<div class='msgboxred'>"+message+"</div>";
	return message;
}

function redtext(message)
{
	message = "<span class='redtext'>"+message+"</span>";
	return message;
}


//Function to enable the delete button [Common Function]
function enabledelete()
{
	if($('#delete'))
	$('#save').attr('disabled',false);
	$('#delete').attr('disabled',false);
}

//Function to enable the save button [Common Function]
function enablesave()
{
	if($('#save'))
	$('#save').attr('disabled',false);
}

//Function to disable the save button [Common Function]
function disablesave()
{
	if($('#save'))
	$('#save').attr('disabled',true);
}

//Function to disable the delete button [Common Function]
function disabledelete()
{
	if($('#delete'))
	$('#delete').attr('disabled',true);
}


//Function to check the particular option in <input type =check> Tag, with the compare value------------------------
function autochecknew(selectid,comparevalue)
{
		var selection = selectid;
		if('yes' == comparevalue)
		{
			$(selection).attr('checked',true)
			return;
		}
		else
		{
			$(selection).attr('checked',false)
			return;
		}
}

