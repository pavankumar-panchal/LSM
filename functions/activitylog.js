//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	$('#tabgroupgridc1link').html('');
	$('#tabgroupgridwb1').html('');
	$('#tabgroupgridwb2').html('');
	$('#tabgroupgridc2link').html('');	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	//reverse the date string from dd-mm-yyyy format to yyyy-mm-dd format
	var fromdatepieces = fromdate.split('-');
	fromdatepieces.reverse();
	var fromdatereversed = fromdatepieces.join('-');
	
	var todatepieces = todate.split('-');
	todatepieces.reverse();
	var todatereversed = todatepieces.join('-');
	
	var start = new Date(fromdatereversed);
	var end =new Date(todatereversed);
	var datediff = new Date(end - start);
	var noofdays = datediff/1000/60/60/24;
	if(noofdays > 6)
	{$('#form-error').html(errormessage('Date limit should be within 7 days')); return false;}
	
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	
	
	var passData = "switchtype=searchactivity&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit) +"&databasefield=" + encodeURIComponent(subselection) + "&username=" +encodeURIComponent($("#username").val()) + "&textfield=" +encodeURIComponent(textfield) +"&eventtype=" +encodeURIComponent($("#eventtype").val()) + "&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passData);
	$('#tabgroupgridc1_1').html(processing());
	var queryString = "../ajax/activitylog.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				error.html('') ;
				$('#tabgroupgridc1_1').html('');
				var response = ajaxresponse.split('|^|');
				if(response[0] == '1')
				{
					gridtab2(2,'tabgroupgrid'); 
					$('#tabgroupgridwb2').html("Total Count :  " + response[3]);
					$('#tabgroupgridc2_1').html(response[1]);
					$('#tabgroupgridc2link').html(response[2]);
				}
				else if(response[0] == '2')
				{
					$('#form-error').html(errormessage(response[1]));
				}
				else
				{
					$('#tabgroupgridc2_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror1());
		}
	});
}

function getmoresearchfilter(startlimit,slnocount,showtype)
{
	$('#tabgroupgridc2link').html('');
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	//reverse the date string from dd-mm-yyyy format to yyyy-mm-dd format
	var fromdatepieces = fromdate.split('-');
	fromdatepieces.reverse();
	var fromdatereversed = fromdatepieces.join('-');
	
	var todatepieces = todate.split('-');
	todatepieces.reverse();
	var todatereversed = todatepieces.join('-');
	
	var start = new Date(fromdatereversed);
	var end =new Date(todatereversed);
	var datediff = new Date(end - start);
	var noofdays = datediff/1000/60/60/24;
	if(noofdays > 6)
	{$('#form-error').html(errormessage('Date limit should be within 7 days')); return false;}
	
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	
	
	var passData = "switchtype=searchactivity&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit) +"&databasefield=" + encodeURIComponent(subselection) + "&username=" +encodeURIComponent($("#username").val()) + "&textfield=" +encodeURIComponent(textfield) +"&eventtype=" +encodeURIComponent($("#eventtype").val()) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000); 

	$('#tabgroupgridc2link').html(processing());
	var queryString = "../ajax/activitylog.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				error.html('');
				$('#getmorelink').html('');
				var ajaxresponse = ajaxresponse.split('|^|');//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{

					$('#tabgroupgridwb2').html("Total Count :  " + ajaxresponse[3]);
					$('#searchresultgrid').html($('#tabgroupgridc2_1').html());
					$('#tabgroupgridc2_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc2link').html(ajaxresponse[2]);
				}
				else if(ajaxresponse[0] == '2')
				{
					$('#form-error').html(errormessage(response[1]));
				}
				else
				{
					$('#tabgroupgridc2_1').html("No datas found to be displayed.");
				}
				
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror1());
		}
	});
}

function filtertoexcel(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	//reverse the date string from dd-mm-yyyy format to yyyy-mm-dd format
	var fromdatepieces = fromdate.split('-');
	fromdatepieces.reverse();
	var fromdatereversed = fromdatepieces.join('-');
	
	var todatepieces = todate.split('-');
	todatepieces.reverse();
	var todatereversed = todatepieces.join('-');
	
	var start = new Date(fromdatereversed);
	var end =new Date(todatereversed);
	var datediff = new Date(end - start);
	var noofdays = datediff/1000/60/60/24;
	if(noofdays > 6)
	{$('#form-error').html(errormessage('Date limit should be within 7 days')); return false;}
	error.html(processing());
	if(command == 'toexcel')
	{
		error.html('');
		$('#submitform').attr("action", "../reportslms/activitylogtoexcel.php") ;
		$('#submitform').submit();
	}
}





//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
	
 	oForm.reset();
	$("#filter-form-error").html('');
	for (i=0; i<elements.length; i++) 
	{
		field_type = elements[i].type.toLowerCase();
	}
	
	switch(field_type)
	{
	
		case "text": 
			elements[i].value = ""; 
			break;
		case "radio":
			if(elements[i].checked == 'databasefield1')
			{
				elements[i].checked = true;
			}
			else
			{
				elements[i].checked = false; 
			}
			break;
		case "checkbox":
  			if (elements[i].checked) 
			{
   				elements[i].checked = true; 
			}
			break;
		case "select-one":
		{
  			 for (var k=0, l=oForm.elements[i].options.length; k<l; k++)
			 {
				 oForm.elements[i].options[k].selected = oForm.elements[i].options[k].defaultSelected;
			 }
				
		}
		break;

		default:$("#districtcodedisplaysearch").html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>') ;
			
	}
}


function changedateformat(date)
{
	if(date != "0000-00-00")
	{
		if(indexOf(date, " "))
		var result = split(" ",date);
		else
		var result = split("[:./-]",date);
		var date = result[2]+"-"+result[1]+"-"+result[0];
	}
	else
	{
		var date = "";
	}
	return date;
}

function gridtab2(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "grid-active-leadtabclass";
	var tabheadclass = "grid-leadtabclass";
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
		}
	}
}

function griddata(startlimit)
{
	$('#tabgroupgridc1link').html('');
	$('#tabgroupgridwb1').html('');
	$('#tabgroupgridwb2').html('');
	$('#tabgroupgridc2link').html('');
	var passdata = "&switchtype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	$("#tabgroupgridc1_1").html(processing());
	var queryString = "../ajax/activitylog.php";
	ajaxobjext91 = $.ajax(
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
				var response1 = response.split("|^|");
				if(response1[0] == '1')
				{
					$("#tabgroupgridc1_1").html(response1[1]);
					$("#tabgroupgridc1link").html(response1[2]);
					$("#tabgroupgridwb1").html("Total Count :  " + response1[3]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror1());
		}
	});		
}

function getmorerecords(startlimit,slnocount,showtype,type)
{
	var passdata = "&switchtype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&dummy=" + Math.floor(Math.random()*10230000000);
	$("#tabgroupgridc1link").html(processing());
	var queryString = "../ajax/activitylog.php";
	ajaxobjext93 = $.ajax(
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
				var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
				if(ajaxresponse[0] == '1')
				{
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
					$('#tabgroupgridc1link').html(ajaxresponse[2]);
					$("#tabgroupgridwb1").html("Total Count :  " + ajaxresponse[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html(scripterror1());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror1());
		}
	});		
}