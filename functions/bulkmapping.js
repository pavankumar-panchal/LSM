
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var customerarray = new Array();
var dealerarray = new Array();
var process1;
var process2;
var process3;
var process4;

function refreshdealerarray()
{
	var form = $("#filterform");
	passData = "submittype=generatedealerlist&dummy=" + Math.floor(Math.random()*1000782200000) ;
	$('#dealerselectionprocess').html(processing());
	queryString = "../ajax/bulkmapping.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				dealerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					dealerarray[i] = response[i];
				}
				getdealerlist();
				$("#dealerselectionprocess").html('');
				$('#displayfilter').hide();
				$("#totalcount").html(dealerarray.length);
			}
		}, 
		error: function(a,b)
		{
			$('#dealerselectionprocess').html(scripterror());
		}
	});		
}



function getdealerlist()
{	
	//$('#tabgroupgridc3_2').hide();
	var form = $('#submitform');	
	var selectbox = $('#dealerlist');
	var numberofcustomers = dealerarray.length;
	//alert(dealerarray);
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	for( var i=0; i<limitlist; i++)
	{
		var splits = dealerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function selectfromlist()
{
	var selectbox = $("#dealerlist option:selected").val();
	$('#detailsearchtext').val($("#dealerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaydealername').html($("#dealerlist option:selected").text());
	newentry();
}


function selectadealer(input)
{
	var selectbox = $('#dealerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getdealerlist();
	}
	else
	{
		//selectbox.options.length = 0;
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		
		var addedcount = 0;
		for( var i=0; i < dealerarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = dealerarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = dealerarray[i];
				}
				var result1 = pattern.test(trimdotspaces(dealerarray[i]).toLowerCase());
				var result2 = pattern.test(dealerarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = dealerarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
		}
	}
}

function dealersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrolldealer('up');
	else if(KeyID == 40)
		scrolldealer('down');
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectadealer(input);
	}
}

function scrolldealer(type)
{	
	var selectbox = $('#dealerlist');
	var totalcus = $("#dealerlist option").length;
	var selectedcus = $("select#dealerlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#dealerlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#dealerlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}


function getregionlist()
{
	var form = $("#filterform");
	var error = $("#form-error");
	var field = $('#prdcategory');
	if(!field.val()) { error.html(errormessage("Please select a category.")); field.focus(); return false; }
	$('#displaycategory').html(field.val());
	//field.val('');
	var selectbox = $("#dealerlist option:selected").val();
	generateassignedlist(selectbox);
	generateunassignedlist(selectbox);
}

function generateassignedlist(dealerid)
{
	$("#lastslno").val(dealerid);
	var form = $("#filterform");
	passData = "submittype=assignedlist&lastslno=" +encodeURIComponent($("#lastslno").val()) + "&category=" + encodeURIComponent($("#prdcategory").val());
	$('#list2div').html(processing());
	queryString = "../ajax/bulkmapping.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				$("#list2div").html('');
				$("#assignedlistdiv").html(response['grid']);
				$("#list2count").html(response['rowcount']);

			}
		}, 
		error: function(a,b)
		{
			$('#dealerselectionprocess').html(scripterror());
		}
	});		
}


function generateunassignedlist(dealerid)
{
	$("#lastslno").val(dealerid);
	var form = $("#filterform");
	passData = "submittype=unassignedlist&lastslno=" +encodeURIComponent($("#lastslno").val()) + "&category=" + encodeURIComponent($("#prdcategory").val());
	$('#list1div').html(processing());
	queryString = "../ajax/bulkmapping.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				$("#list1div").html('');
				$("#unassignedlistdiv").html(response['grid']);
				$("#list1count").html(response['rowcount']);
			}
		}, 
		error: function(a,b)
		{
			$('#dealerselectionprocess').html(scripterror());
		}
	});		
}


//function to add values of the selected option to select box
function addentry(listvalue)
{
	//Get the Select Box as an object
	var selectbox = document.getElementById('list1');
	
	//Check if any item is select. Else, prompt to select a item.
	if(selectbox.selectedIndex < 0)
	{
		alert("Select a Region to Add.");
		return false;
	}
	
	
	//Take the value and Text of selected product from selected index.
	var addlistvalue = selectbox.options[selectbox.selectedIndex].value;
	var addlisttext = selectbox.options[selectbox.selectedIndex].text;

	//When double clicked on a disabled, the other selected will be passed. So, compare the double clicked product value with selected value and return false.
	if(listvalue)
	{
		if(listvalue != addlistvalue)
			return false;
	}
	//Get the second Select Box as an object
	var secondselectbox = document.getElementById('list2');
	
	//Add the value to second select box
	var newindexforadding = secondselectbox.length;
	secondselectbox.options[newindexforadding] = new Option(addlisttext,addlistvalue);
	secondselectbox.options[newindexforadding].setAttribute('ondblclick', 'deleteentry("' + addlistvalue + '");');

	
	//Disable the added option in first select box
	if (selectbox.options[selectbox.selectedIndex].selected) 
	{
		selectbox.remove(selectbox.selectedIndex);
	}
	$("#list2count").html($("#list2 option").length);
	$("#list1count").html($("#list1 option").length);
	sortarray();
}	

//function to remove values of the selected option from select box -Meghana[23/11/2009]
function deleteentry(productcode)
{
	//alert(productcode);
	//Get the select boxes as objects
	var selectbox = document.getElementById('list1');
	var secondselectbox = document.getElementById('list2');
	
	//Check if any product is select. Else, prompt to select a product.
	if(secondselectbox.selectedIndex < 0)
	{
		alert("Select a Region to Remove.");
		return false;
	}

	//Take the value and Text of selected product from selected index.
	var dellistvalue = secondselectbox.options[secondselectbox.selectedIndex].value;
	var dellisttext = secondselectbox.options[secondselectbox.selectedIndex].text;
	
	//Run a loop for whole select box [2] and remove the entry where value is deletable
	for(i = 0; i < secondselectbox.length; i++)
    {
		loopvalue = secondselectbox.options[i].value;
		if(loopvalue == dellistvalue)
		{
			//secondselectbox.options[i] = null;
			secondselectbox.remove(i);
		}
	}
	var newindexforadding = selectbox.length;
	selectbox.options[newindexforadding] = new Option(dellisttext,dellistvalue);
	$("#list1count").html($("#list1 option").length);
	$("#list2count").html($("#list2 option").length);
	sortarray();
}


//function to remove all values of the selected option from select box
function deleteallentry(productcode)
{
		//Get the select boxes as objects
		var listarray = new Array();
		var alllistarray = new Array();
		var alllistarraytext = new Array();
		var selectbox = document.getElementById('list1');
		var secondselectbox = document.getElementById('list2');
		var secoundvalues = document.getElementById('list1');
		for(var i=0; i < secoundvalues.length; i++ )
		{
			listarray[i] = secoundvalues[i].value;

		}
	
		var ckvalues = document.getElementById('list2');
		for(var i=0; i < ckvalues.length; i++ )
		{
			alllistarray[i] = ckvalues[i].value;
			alllistarraytext[i] = ckvalues[i].text;
			var newindexforadding = selectbox.length;
			selectbox.options[newindexforadding] = new Option(alllistarraytext[i],alllistarray[i]);
		}
	
		//Run a loop for whole select box [2] and remove the entry where value is deletable
		for(i = 0; i < alllistarray.length; i++)
		{
				secondselectbox.options[secondselectbox.length -1] = null;
		}
	$("#list1count").html($("#list1 option").length);
	$("#list2count").html($("#list2 option").length);
	sortarray();

}

function sortarray()
{
	var list1array = Array();
	var list2array = Array();

	var list1values = document.getElementById('list1');
	for(var i=0; i < list1values.length; i++ )
	{
		
		list1array[i] = list1values[i].text+'^'+list1values[i].value;
	}
	list1array.sort();
	for( var i=0; i< list1array.length; i++)
	{
		var splits = list1array[i].split("^");
		list1values.options[i] = new Option(splits[0], splits[1]);
		list1values.options[i].setAttribute('ondblclick', 'addentry("' + splits[1] + '");');
	}
	var list2values = document.getElementById('list2');
	for(var i=0; i < list2values.length; i++ )
	{
		
		list2array[i] = list2values[i].text+'^'+list2values[i].value;
	}
	list2array.sort();
	for( var i=0; i< list2array.length; i++)
	{
		var splits1 = list2array[i].split("^");
		list2values.options[i] = new Option(splits1[0], splits1[1]);
		list2values.options[i].setAttribute('ondblclick', 'deleteentry("' + splits1[1] + '");');
	}

}


function formsubmit()
{
	var form =$('#submitform');
	var error = $('#form-error');
	var listarray = new Array();
	
	if($('#dealerlist').val() == '') { error.html(errormessage("Please select a dealer.")); field.focus(); return false; }
	var field =$('#prdcategory');
	if(!field.val()) { error.html(errormessage("Please select a Category")); field.focus(); return false; }

	//Check if any product is select. Else, prompt to select a product.
	var ckvalues = document.getElementById('list2');
	for(var i=0; i < ckvalues.length; i++ )
	{
		listarray[i] = ckvalues[i].value;
	}
	if (listarray.length <1) 
	 { error.html(errormessage("Please select a region to Map")); ckvalues.focus(); return false; }
	
	
		var passData = "";

	   passData =  "submittype=save&lastslno=" + $('#dealerlist').val() + "&category=" + $('#prdcategory').val() + "&listarray=" + listarray +"&dummy=" + Math.floor(Math.random()*100000000);
		
		$('#form-error').html(processing());
		queryString = "../ajax/bulkmapping.php";
		ajaxcall0 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse;//alert(response)
					if(response['errorcode'] == '1')
					{
						error.html(successmessage(response['errormeg']));//alert(error.innerHTML)
					}
					
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});	
}




function resetlistvalues()
{
	var selectbox = $("#dealerlist option:selected").val();
	generateassignedlist(selectbox);
	generateunassignedlist(selectbox);
}

function newentry()
{
	var list1 = document.getElementById('list1');
	var list2 = document.getElementById('list2');
	$('option', list1).remove();
	$('option', list2).remove();
	$('#prdcategory').removeAttr('disabled');
	$('#prdcategory').val('');
	$('#displaycategory').html('');
}




