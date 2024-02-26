// JavaScript Document

function griddata(startlimit)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mapping1.php";
	ajaxobjext24 = $.ajax(
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
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]);
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess").html('');
				}
				else
				{
					$("#tabgroupgridc1_1").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}

function filtering()
{
	var form = $("#filter");
	var textfield = $("#searchcriteria").val();
	var subselection = $('input[name=databasefield]:checked').val();
	$("#srchhiddenfield").val(textfield);
	$("#subselhiddenfield").val(subselection) ; 
	$("#gridprocess").html(processing()+'  ' + '<span onclick = "abortmappingajaxprocess(\'initial\')" class="abort">(STOP)</span>');
	var passdata = "&submittype=filter&searchtext=" + encodeURIComponent(textfield) + "&subselection=" + subselection +"&dummy=" + Math.floor(Math.random()*10230000000); 
	var queryString = "../ajax/mapping1.php";
	ajaxobjext25 = $.ajax(
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
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]);
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess").html('');
				}
				else
				{
					$("#gridprocess").html('');
					$("#tabgroupgridc1_1").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}


function gridtoform(id)
{
	$("#gridprocess").html( processing());
	var passdata = "&submittype=gridtoform&form_recid="+id+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mapping1.php";
	ajaxobjext26 = $.ajax(
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
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$('#proceedlink').html('<a href="./mapping.php?dlrid=' + ajaxresponse[1] + '"><strong>Proceed &gt;&gt;</strong></a>') ;
					$('#dealerdisplay').html(ajaxresponse[2]);
				}
				else
				{
					$('#dealerdisplay').html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$('#dealerdisplay').html(scripterror());
		}
	});		
}


function getmorerecords(startlimit,slnocount,showtype,type)
{
	
	var form = $("#filter");
	var textfield = $("#srchhiddenfield").val();
	var subselection = $("#subselhiddenfield").val();
	if(type == 'dealer')
	{
		$("#gridprocess").html(processing());
		var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	}
	else if(type == 'search')
	{
		$("#gridprocess").html(processing()+'  ' + '<span onclick = "abortmappingajaxprocess(\'showmore\')" class="abort">(STOP)</span>');
		var passdata = "&submittype=filter&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype+"&searchtext=" + encodeURIComponent(textfield) + "&subselection=" + subselection +"&dummy=" + Math.floor(Math.random()*10230000000);
	}
	var queryString = "../ajax/mapping1.php";//alert(passdata);
	ajaxobjext27 = $.ajax(
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
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('');
					$('#resultgrid').html($('#tabgroupgridc1_1').html()) ;
					$('#tabgroupgridc1_1').html( $('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#getmorelink').html(ajaxresponse[2]);
					$('#totalcount').html("Total Count :  " + ajaxresponse[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}

function clear1()
{
	var form = $("#filter");
	$("#srchhiddenfield").html('');	
	form[0].reset();
	$('#searchbox').html('');
}

function abortmappingajaxprocess(type)
{
	if(type == 'initial')
	{
		ajaxobjext25.abort();
		$('#gridprocessnv').html('');
	}
	else if(type == 'showmore')
	{
		ajaxobjext27.abort();
		$('#gridprocessnv').html('');
	}	
}