// JavaScript Document
function griddata(startlimit)
{
	var form = $("#mastersearchform");
	var msg_box = $("#msg_box");
	var name = $("#form_search");    
	if (name.val() == '')
	{ 
		msg_box.html(errormessage("Please Enter a Keyword.")); 
		name.focus(); 
		return false;
	}
		//alert(startlimit);
	$("#gridprocess").html(processing()) ;
	var passdata = "&submittype=search&form_recid="+$("#form_recid").val() + "&form_search=" + $("#form_search").val() + "&dummy="+Math.floor(Math.random()*1000782200000);
	//alert(passdata);
	var querystring = "../ajax/mastersearch.php";
	ajaxobjext6 = $.ajax(
	{
		type: "POST",url: querystring, data: passdata, cache: false,
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
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('') ;
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]);
					//$("#totalcount").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess").html('Users Master Search');
					if (name.val() != '')
					{ 
						divenable('dealerdisplay','save');
						divenable('submitlink','save');
						divenable('editdata','save');
						document.getElementById('msg_box').innerHTML='';
					}
				}
				else
				{
					$("#gridprocess").html(scripterror1()) ;
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1()) ;
		}
	});	
}

function gridtoformsubadmin(id)
{
	$("#gridprocess").html( processing());
	var passdata = "&submittype=gridtoformsubadmin&form_recid="+id+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mastersearch.php";
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
					$('#submitlink').html('<a href="./subadmin-1.php?subid=' + ajaxresponse[1] + '"><strong>Proceed &gt;&gt;</strong></a>') ;
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

function gridtoformmgr(id)
{			
	$("#gridprocess").html( processing());
	var passdata = "&submittype=gridtoformmgr&form_recid="+id+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mastersearch.php";
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
					$('#submitlink').html('<a href="./manager-1.php?mgrid=' + ajaxresponse[1] + '"><strong>Proceed &gt;&gt;</strong></a>') ;
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
function gridtoformdlr(id)
{			
	$("#gridprocess").html( processing());
	var passdata = "&submittype=gridtoformdlr&form_recid="+id+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/mastersearch.php";
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
					$('#submitlink').html('<a href="./dealer-1.php?dlrid=' + ajaxresponse[1] + '"><strong>Proceed &gt;&gt;</strong></a>') ;
					$('#dealerdisplay').html(ajaxresponse[4]);
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

/*function getmorerecords(startlimit,slnocount,showtype)
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=griddata&startlimit="+startlimit +"&slnocount="+slnocount+"&showtype="+showtype; //alert(passdata)
	var querystring = "../ajax/mastersearch.php";
	ajaxobjext9 = $.ajax(
	{
		type: "POST",url: querystring, data: passdata, cache: false,
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
				if(ajaxresponse[0] == '1')
				{
					$("#gridprocess").html('') ;
					$('#resultgrid').html($('#tabgroupgridc1_1').html()) ;
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
					$('#getmorelink').html(ajaxresponse[2]);
					$('#totalcount').html("Total Count :  " + ajaxresponse[3]) ;
					$("#gridprocess").html('Sub-Admin Register');
				}
				else
				{
					$("#gridprocess").html(scripterror1()) ;
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html(scripterror1()) ;
		}
	});		
}*/

function divenable(hid, sho) 
{
	var dis = (sho.checked) ? "none" : "block";
	document.getElementById(hid).style.display = dis;
}

function newentry()
{
	$("#form_recid").val('');
	$("#mastersearchform")[0].reset();
	document.getElementById('msg_box').innerHTML='';
	document.getElementById("dealerdisplay").style.display = "none";
	document.getElementById("submitlink").style.display = "none";
	document.getElementById("editdata").style.display = "none";
}