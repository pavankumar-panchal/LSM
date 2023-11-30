var datePickerDivID = "datepicker";
var iFrameDivID = "datepickeriframe";
//Declaring variables to define the week days and months-----------------------------------------------------------
var dayArrayShort = new Array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
 
//Function to create Ajax Object-----------------------------------------------------------------------------------
//Variables to define the format of the date-----------------------------------------------------------------------
var defaultDateSeparator = "-";
var defaultDateFormat = "dmy";
var dateSeparator = defaultDateSeparator;
var dateFormat = defaultDateFormat;

//This is the main function you'll call from the onClick event of a button. Normally, you'll have something like this on your HTML page: Start Date: <input name="StartDate"><input type=button value="select" onclick="displayDatePicker('StartDate');"> That will cause the datepicker to be displayed beneath the StartDate field and any date that is chosen will update the value of that field. If you'd rather have the datepicker display beneath the button that was clicked, you can code the button like this: <input type=button value="select" onclick="displayDatePicker('StartDate', this);"> So, pretty much, the first argument (dateFieldName) is a string representing thename of the field that will be modified if the user picks a date, and the second argument (displayBelowThisObject) is optional and represents an actual node on the HTML document that the datepicker should be displayed below. In version 1.1 of this code, the dtFormat and dtSep variables were added, allowing you to use a specific date format or date separator for a given call to this function. Normally, you'll just want to set these defaults globally with the defaultDateSeparator and defaultDateFormat variables, but it doesn't hurt anything to add them as optional parameters here. An example of use is: <input type=button value="select" onclick="displayDatePicker('StartDate', false, 'dmy', '.');"> This would display the datepicker beneath the StartDate field (because the displayBelowThisObject parameter was false), and update the StartDate field with the chosen value of the datepicker using a date format of dd.mm.yyyy-------------------------------------------------------------------------
function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)
{
	var targetDateField = document.getElementsByName (dateFieldName).item(0);
	if (!displayBelowThisObject)
		displayBelowThisObject = targetDateField;
	if (dtSep)
		dateSeparator = dtSep;
	else
		dateSeparator = defaultDateSeparator;
	if (dtFormat)
		dateFormat = dtFormat;
	else
		dateFormat = defaultDateFormat;
	
	var x = displayBelowThisObject.offsetLeft;
	var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
	
	var parent = displayBelowThisObject;
	while (parent.offsetParent) 
	{
		parent = parent.offsetParent;
		x += parent.offsetLeft;
		y += parent.offsetTop ;
	}
	
	drawDatePicker(targetDateField, x, y);
}


//Draw the datepicker object (which is just a table with calendar elements) at the specified x and y coordinates, using the targetDateField object as the input tag that will ultimately be populated with a date. This function will normally be called by the displayDatePicker function.
function drawDatePicker(targetDateField, x, y)
{
	var dt = getFieldDate(targetDateField.value );
	if (!document.getElementById(datePickerDivID)) 
	{
		var newNode = document.createElement("div");
		newNode.setAttribute("id", datePickerDivID);
		newNode.setAttribute("class", "dpDiv");
		newNode.setAttribute("style", "visibility: hidden;");
		document.body.appendChild(newNode);
	}
	var pickerDiv = document.getElementById(datePickerDivID);
	pickerDiv.style.position = "absolute";
	pickerDiv.style.left = x + "px";
	pickerDiv.style.top = y + "px";
	pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");
	pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");
	pickerDiv.style.zIndex = 10000;
	
	refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
}


//This is the function that actually draws the datepicker calendar-----------------------------------------------------
function refreshDatePicker(dateFieldName, year, month, day)
{
	var thisDay = new Date();
	
	if ((month >= 0) && (year > 0)) 
	{
		thisDay = new Date(year, month, 1);
	} 
	else 
	{
		day = thisDay.getDate();
		thisDay.setDate(1);
	}
	
	var crlf = "\r\n";
	var TABLE = "<table cols=7 class='dpTable'>" + crlf;
	var xTABLE = "</table>" + crlf;
	var TR = "<tr class='dpTR'>";
	var TR_title = "<tr class='dpTitleTR'>";
	var TR_days = "<tr class='dpDayTR'>";
	var TR_todaybutton = "<tr class='dpTodayButtonTR'>";
	var xTR = "</tr>" + crlf;
	var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";
	var TD_title = "<td colspan=5 class='dpTitleTD'>";
	var TD_buttons = "<td class='dpButtonTD'>";
	var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";
	var TD_days = "<td class='dpDayTD'>";
	var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";  
	var xTD = "</td>" + crlf;
	var DIV_title = "<div class='dpTitleText'>";
	var DIV_selected = "<div class='dpDayHighlight'>";
	var xDIV = "</div>";
	
	var html = TABLE;
	
	html += TR_title;
	html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;
	html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;
	html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;
	html += xTR;
	
	html += TR_days;
	for(i = 0; i < dayArrayShort.length; i++)
	html += TD_days + dayArrayShort[i] + xTD;
	html += xTR;
	
	html += TR;
	
	for (i = 0; i < thisDay.getDay(); i++)
		html += TD + "&nbsp;" + xTD;
	
	do 
	{
		dayNum = thisDay.getDate();
		TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";
		if (dayNum == day)
			html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
		else
			html += TD + TD_onclick + dayNum + xTD;
		if (thisDay.getDay() == 6)
			html += xTR + TR;
		thisDay.setDate(thisDay.getDate() + 1);
	} 
	while (thisDay.getDate() > 1)
	
	if (thisDay.getDay() > 0) 
	{
		for (i = 6; i > thisDay.getDay(); i--)
			html += TD + "&nbsp;" + xTD;
	}
	html += xTR;
	
	var today = new Date();
	var todayString = "Today is " + dayArrayMed[today.getDay()] + ", " + monthArrayMed[ today.getMonth()] + " " + today.getDate();
	html += TR_todaybutton + TD_todaybutton;
	html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>This Month</button> ";
	html += "<button class='dpTodayButton' onClick='updateDateField(\"" + dateFieldName + "\");'>Close</button>";
	html += xTD + xTR;
	
	html += xTABLE;
	
	document.getElementById(datePickerDivID).innerHTML = html;
	adjustiFrame();
}


//Convenience function for writing the code for the buttons that bring us back or forward a month. 
function getButtonCode(dateFieldName, dateVal, adjust, label)
{
	var newMonth = (dateVal.getMonth () + adjust) % 12;
	var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
	if (newMonth < 0) 
	{
		newMonth += 12;
		newYear += -1;
	}
	return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";
}


//Convert a JavaScript Date object to a string, based on the dateFormat and dateSeparator variables at the beginning of this script library.
function getDateString(dateVal)
{
	var dayString = "00" + dateVal.getDate();
	var monthString = "00" + (dateVal.getMonth()+1);
	dayString = dayString.substring(dayString.length - 2);
	monthString = monthString.substring(monthString.length - 2);
	
	switch (dateFormat) 
	{
		case "dmy" :
			return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
		case "ymd" :
			return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
		case "mdy" :
		default :
			return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
	}
}


//Convert a string to a JavaScript Date object. 
function getFieldDate(dateString)
{
	var dateVal;
	var dArray;
	var d, m, y;
	try 
	{
		dArray = splitDateString(dateString);
		if (dArray) 
		{
			switch (dateFormat) 
			{
				case "dmy" :
					d = parseInt(dArray[0], 10);
					m = parseInt(dArray[1], 10) - 1;
					y = parseInt(dArray[2], 10);
				break;
				case "ymd" :
					d = parseInt(dArray[2], 10);
					m = parseInt(dArray[1], 10) - 1;
					y = parseInt(dArray[0], 10);
				break;
				case "mdy" :
				default :
					d = parseInt(dArray[1], 10);
					m = parseInt(dArray[0], 10) - 1;
					y = parseInt(dArray[2], 10);
				break;
			}
			dateVal = new Date(y, m, d);
		} 
		else if (dateString) 
		{
			dateVal = new Date(dateString);
		} 
		else 
		{
			dateVal = new Date();
		}
	} 
	catch(e) 
	{
		dateVal = new Date();
	}
	return dateVal;
}


//Try to split a date string into an array of elements, using common date separators. If the date is split, an array is returned; otherwise, we just return false.
function splitDateString(dateString)
{
	var dArray;
	if (dateString.indexOf("/") >= 0)
		dArray = dateString.split("/");
	else if (dateString.indexOf(".") >= 0)
		dArray = dateString.split(".");
	else if (dateString.indexOf("-") >= 0)
		dArray = dateString.split("-");
	else if (dateString.indexOf("\\") >= 0)
		dArray = dateString.split("\\");
	else
		dArray = false;
	return dArray;
}

function updateDateField(dateFieldName, dateString)
{
	var targetDateField = document.getElementsByName (dateFieldName).item(0);
	if (dateString)
		targetDateField.value = dateString;
	var pickerDiv = document.getElementById(datePickerDivID);
	pickerDiv.style.visibility = "hidden";
	pickerDiv.style.display = "none";
	adjustiFrame();
	targetDateField.focus();
	if ((dateString) && (typeof(datePickerClosed) == "function"))
		datePickerClosed(targetDateField);
}


//Use an "iFrame shim" to deal with problems where the datepicker shows up behind selection list elements, if they're below the datepicker. The problem and solution are described at: http://dotnetjunkies.com/WebLog/jking/archive/2003/07/21/488.aspx http://dotnetjunkies.com/WebLog/jking/archive/2003/10/30/2975.aspx 
function adjustiFrame(pickerDiv, iFrameDiv)
{
	var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);
	if (is_opera)
		return;
	try 
	{
		if (!document.getElementById(iFrameDivID)) 
		{
			var newNode = document.createElement("iFrame");
			newNode.setAttribute("id", iFrameDivID);
			newNode.setAttribute("src", "javascript:false;");
			newNode.setAttribute("scrolling", "no");
			newNode.setAttribute ("frameborder", "0");
			document.body.appendChild(newNode);
		}
		
		if (!pickerDiv)
			pickerDiv = document.getElementById(datePickerDivID);
		if (!iFrameDiv)
			iFrameDiv = document.getElementById(iFrameDivID);
		try 
		{
			iFrameDiv.style.position = "absolute";
			iFrameDiv.style.width = pickerDiv.offsetWidth;
			iFrameDiv.style.height = pickerDiv.offsetHeight ;
			iFrameDiv.style.top = pickerDiv.style.top;
			iFrameDiv.style.left = pickerDiv.style.left;
			iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;
			iFrameDiv.style.visibility = pickerDiv.style.visibility ;
			iFrameDiv.style.display = pickerDiv.style.display;
		} 
		catch(e) 
		{
		}
	} 
	catch (ee) 
	{
	}
}
