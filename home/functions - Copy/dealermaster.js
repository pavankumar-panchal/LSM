// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing()) ;
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100); 
	var queryString = "../ajax/dealermaster.php";
	ajaxobjext14 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response1 = response.split("|^|");
			if(response1[0] == '1')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html(' => [Total number of Dealers (' + response1[3] +' Records)]') ;
			}
			else if(response1[0] == '2')
			{
				$("#tabgroupgridc1_1").html(response1[1]);
				$("#getmorelink").html(response1[2]);
				$("#gridprocess").html(' => [Total number of Dealers (' + response1[3] +' Records)]') ;
			}
			else
			{
				$("#gridprocess").html(scripterror1()) ;
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});		
}


function formsubmit(command)
{
	var form = $("#dealerform");
	var msg_box = $("#msg_box");

	var field = $("#form_companyname");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Company Name.")) ; field.focus(); return false;}
	var field = $("#form_name");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Name.")); field.focus(); return false;}
	var field = $("#form_address");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Address.")) ; field.focus(); return false;}
	var field = $("#form_state");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a State.")); field.focus(); return false;}
	var field = $("#form_district");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select a District.")) ; field.focus(); return false;}
	var field =$("#form_phone");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Phone Number.")) ; field.focus(); return false;}
	if(validatephone(field.val()) == false)
	{ msg_box.html(errormessage("Please Enter valid Phone Number.")) ; field.focus(); return false;}
	var field = $("#form_cell");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the Cell Number.")) ; field.focus(); return false;}
	var field = $("#form_cell");  
	if (!validatecell(field.val()))
	{ msg_box.html(errormessage("Please Enter valid Cell Number.")) ; field.focus(); return false;}
	var field = $("#form_email");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the email ID.")); field.focus(); return false;}
	if(checkemail(field.val()) == false)
	{ msg_box.html(errormessage("Please Enter the Valid email ID.")) ; field.focus(); return false;}
	var field = $("#form_manager");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select the Manager.")); field.focus(); return false;}
	
	var field = $("#form_branch");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Select the Branch.")); field.focus(); return false;}
	
	var field = $("#form_username");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the username.")); field.focus(); return false;}
	var field = $("#form_password");  
	if (!field.val())
	{ msg_box.html(errormessage("Please Enter the password.")); field.focus(); return false;}
	msg_box.html(processing()) ;
	if($("#form_disablelogin:checked").val() == 'on') disablelogin = 'yes'; else disablelogin = 'no';
	if($("#form_relyonexecutive:checked").val() == 'on') relyonexecutive = 'yes'; else relyonexecutive = 'no';
	
	if(command == 'save')
	{
		var passdata = "&submittype=save&form_recid=" + $("#form_recid").val() + "&form_companyname=" + encodeURIComponent($("#form_companyname").val()) + "&form_name=" + encodeURIComponent($("#form_name").val()) + "&form_address=" + encodeURIComponent($("#form_address").val()) + "&form_state=" + encodeURIComponent($("#form_state").val()) + "&form_district=" + encodeURIComponent($("#form_district").val()) + "&form_phone=" + encodeURIComponent($("#form_phone").val()) + "&form_cell=" + encodeURIComponent($("#form_cell").val()) + "&form_email=" + encodeURIComponent($("#form_email").val()) + "&form_website=" + encodeURIComponent($("#form_website").val()) + "&form_manager=" + encodeURIComponent($("#form_manager").val()) + "&form_username=" + encodeURIComponent($("#form_username").val()) + "&form_password=" + encodeURIComponent($("#form_password").val())+"&form_disablelogin="+disablelogin+"&form_relyonexecutive="+relyonexecutive+ "&form_branch=" + encodeURIComponent($("#form_branch").val())+"&dummy=" + Math.floor(Math.random()*10230000000);//alert(passdata)
		var queryString = "../ajax/dealermaster.php";
	}
	else if(command == 'delete')
	{
		var passdata = "&submittype=delete&form_recid=" + $("#form_recid").val() +"&dummy=" + Math.floor(Math.random()*10230000000);
		var queryString = "../ajax/dealermaster.php";
	}
	ajaxobjext15 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse2 = response.split("^");
			if(ajaxresponse2[0] == '1')
			{
				msg_box.html(successmessage(ajaxresponse2[1]));
				griddata('');
				newentry();
			}
			else if(ajaxresponse2[0] == '2')
			{
				msg_box.html(errormessage(ajaxresponse2[1])) ;
				griddata('');
			}
			else
			{
				msg_box.html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
		}
	});		
}

function newentry()
{
//	document.getElementById("msg_box").innerHTML = '';
	$("#form_recid").val('');
	$("#districtdiv").html('<select name="form_district" id="form_district"><option value="" selected="selected">- - - -Select a State First - - - -</option></select>') ;
	$("#dealerform")[0].reset();
	enablesave();
	disabledelete();
}


function gridtoform(id)
{
	var form = $("#dealerform");
	$("#gridprocess").html(processing()) ;
	var passdata = "&submittype=gridtoform&form_recid=" + id +"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/dealermaster.php";
	ajaxobjext16 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response3 = response.split("^");  //alert(response3);
			if(response3[0] == '1')
			{
				$("#gridprocess").html('');
				$('#form_recid').val(response3[1]);
				autoselect('form_state',response3[2]);
				districtselect('form_district',response3[3]);
				$('#form_name').val(response3[4]);
				$('#form_companyname').val(response3[5]);
				$('#form_address').val(response3[6]) ;
				$('#form_cell').val(response3[7]);
				$('#form_phone').val(response3[8]);
				$('#form_email').val(response3[9]) ;
				$('#form_website').val(response3[10]);
				autoselect('form_manager',response3[11]);
				$('#form_username').val(response3[12]);
				$('#form_password').val(response3[13]);
				$('#hiddenpwd').html(response3[13]);
				//alert(response3[13]);
				autochecknew($("#form_disablelogin"),response3[14]);
				autochecknew($("#form_relyonexecutive"),response3[15]);
				autoselect('form_branch',response3[16]);
				$('#msg_box').html('');
				enabledelete();
				enablesave();
			}
			else
			{
				$('#msg_box').html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			msg_box.html(scripterror());
		}
	});		

}

function districtselect(selectid,comparevalue)
{
	var state = $('#form_state').val();
	var districtdiv = $('#districtdiv');
	var queryString = "../ajax/dealermaster.php";
	var passdata = "&submittype=state&statecode=" + state +"&dummy=" + Math.floor(Math.random()*100032680100);
	ajaxobjext17 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
	
			districtdiv.html(response);
			if(selectid && comparevalue)
				autoselect(selectid,comparevalue);
			
		}, 
		error: function(a,b)
		{
			districtdiv.html(scripterror());
		}
	});		
}

function filtering()
{
	var form = $("#filterform");
	var textfield = $("#searchcriteria").val(); //alert(textfield)
	var subselection =$('input[name=databasefield]:checked').val();
	var disabled = $('#disabled').val();//alert(disabled);
	$('#srchhiddenfield').val(textfield); //alert($('#srchhiddenfield').val())
	$('#subselhiddenfield').val(subselection); //alert(textfield.length);
	$("#gridprocess").html(processing() +'  '+ '<span onclick = "abortdealerfilterajaxprocess(\'initial\')" class="abort">(STOP)</span>');
	$('#searchbox').html('');
	var passdata = "&submittype=filter&searchtext=" + $("#srchhiddenfield").val() + "&subselection=" + subselection+ "&disabled=" + disabled +"&dummy=" + Math.floor(Math.random()*10230000000); //alert(passdata)
	var queryString = "../ajax/dealermaster.php";
	ajaxobjext18 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			var response5 = response.split("|^|");
			if(response5[0] == '1')
			{
				$("#tabgroupgridc1_1").html(response5[1]);
				$("#getmorelink").html(response5[2]);
				$("#gridprocess").html(' => Filter Applied (' + response5[3] +' Records)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a onclick="griddata();" style="cursor:pointer"><strong>Remove Filter</strong></a>]');
			}
			else
			{
				$("#gridprocess").html(scripterror1());
			}
			
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});			
}

function getmorerecords(startlimit,slnocount,showtype,type)
{
	var disabled = $('#disabled').val();
	var form1 = $("#filterform");
	var textfield = $("#srchhiddenfield").val();
	var subselection = $("#subselhiddenfield").val(); 
	if(type == 'dealer')
	{
		$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortdealerajaxprocess()" class="abort">(STOP)</span>');
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'search')
	{
		$("#gridprocess").html(processing()+'  '+ '<span onclick = "abortdealerfilterajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&searchtext="+ encodeURIComponent(textfield) + "&subselection="+ subselection + "&disabled=" + disabled +"&dummy="+ Math.floor(Math.random()*10230000000);
	}
	var querystring = "../ajax/dealermaster.php"; //alert(passdata)
	ajaxobjext19 = $.ajax(
	{
		type: "POST",url: querystring, data: passdata, cache: false,
		success: function(response,status)
		{	
			var ajaxresponse = response.split('|^|');//alert(ajaxresponse);
			if(ajaxresponse[0] == '1')
			{
				$('#resultgrid').html( $('#tabgroupgridc1_1').html()) ;
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
				$('#getmorelink').html(ajaxresponse[2]);
				$("#gridprocess").html(' => [Total number of Dealers (' + ajaxresponse[3] +' Records)]') ;
			}
			else if(ajaxresponse[0] == '2')
			{
				$('#resultgrid').html($('#tabgroupgridc1_1').html()) ;
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1])  ;
				$('#getmorelink').html(ajaxresponse[2]);
				$("#gridprocess").html(' => [Total number of Dealers (' + ajaxresponse[3] +' Records)]') ;
			}
			else
			{
				$("#gridprocess").html(scripterror1());
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1());
		}
	});		
	
}

function clear1()
{
	var form = $("#filterform");
	form[0].reset();
	$('#searchbox').html('');
}

function newtog()
{
	$('#filterform').toggle();
}

function abortdealerfilterajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext18.abort();	
		$("#gridprocess").html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext19.abort();
		$("#gridprocess").html('');
	}
}

function abortdealerajaxprocess()
{
	ajaxobjext19.abort();
	$("#gridprocess").html('');
}