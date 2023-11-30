function griddata()
{
	$("#gridprocess").html(processing());
	var outputselect = $("#rd_box");
	var passdata = "&submittype=griddata&form_dlrlist=" + encodeURIComponent($("#form_dlrlist").val())+"&dummy=" + Math.floor(Math.random()*100032680100);
	//alert (passdata);
	var queryString = "../ajax/statetransfermapping.php";
	ajaxobjext31 = $.ajax(
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
				var ajaxresponse = response.split('^'); //alert(ajaxresponse);
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]) ;
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
					/*outputselect.html(ajaxresponse[4]);*/
					$("#gridprocess").html('');
					
					if(ajaxresponse[3] > '0')
					{
						divenable('data1','form_dlrlist');
						divenable('dlrtransfer','form_dlrlist');
						document.getElementById('rd_box').innerHTML='';
					}
					else{ outputselect.html('No Record Found')}
				}
				else
				{$("#tabgroupgridc1_1").html(scripterror());}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess").html('');
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}

function state()
{
	$("#gridprocess").html(processing());
	var passdata = "&submittype=gridstate&form_dlrlist=" + encodeURIComponent($("#form_dlrlist").val())+"&form_state=" + encodeURIComponent($("#form_state").val())+"&dummy=" + Math.floor(Math.random()*100032680100);
	//alert (passdata);
	var queryString = "../ajax/statetransfermapping.php";
	ajaxobjext31 = $.ajax(
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
				var ajaxresponse = response.split('^'); //alert(ajaxresponse);
				$("#gridprocess").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$("#getmorelink").html(ajaxresponse[2]) ;
					$("#totalcount").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess").html('');
					
					if(ajaxresponse[3] > '0')
					{
						divenable('data1','form_dlrlist');
						divenable('dlrtransfer','form_dlrlist');
					}
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

function gridlist()
{
	$("#gridprocess1").html(processing());
	var passdata = "&submittype=gridlist&form_dlrlist1=" + encodeURIComponent($("#form_dlrlist1").val())+"&dummy=" + Math.floor(Math.random()*100032680100);
	//alert (passdata);
	var queryString = "../ajax/statetransfermapping.php";
	ajaxobjext31 = $.ajax(
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
				var ajaxresponse = response.split('^'); //alert(ajaxresponse);
				$("#gridprocess1").html('');
				if(ajaxresponse[0] == '1')
				{
					$("#tabgroupgridc2_1").html(ajaxresponse[1]);
					$("#getmorelink1").html(ajaxresponse[2]) ;
					$("#totalcount1").html("Total count : " + ajaxresponse[3]);
					$("#gridprocess1").html('');
					divenable('data2','form_dlrlist1');
					document.getElementById("transferdata").disabled = false; 
				}
				else
				{
					$("#tabgroupgridc2_1").html(scripterror());
				}
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocess1").html('');
			$("#tabgroupgridc2_1").html(scripterror());
		}
	});		
}

function transferdata()
{
	var name = $("#form_dlrlist1");  
	var state = $("#form_state");  
	var dealer = $("#form_dlrlist"); 
	var msg_box = $("#msg_box");
	if (dealer.val() == '')
	{ 
		msg_box.html(errormessage("Kindly Select Dealer Name.")); 
		dealer.focus(); 
		return false;
	}
	else if (state.val() == '')
	{ 
		msg_box.html(errormessage("Sorry!! Kindly Select 'All OR State name' From 'Select a State name'.")); 
		state.focus(); 
		return false;
	}
	else if (name.val() == '')
	{ 
		msg_box.html(errormessage("Kindly Select Data Transfer To.")); 
		name.focus(); 
		return false;
	}

	var passdata = "&submittype=transferdata&form_dlrlist1=" + encodeURIComponent($("#form_dlrlist1").val())+"&form_state=" + encodeURIComponent($("#form_state").val())+"&form_dlrlist=" + encodeURIComponent($("#form_dlrlist").val())+"&dummy=" + Math.floor(Math.random()*100032680100);
	alert (passdata);
	var queryString = "../ajax/statetransfermapping.php";
	ajaxobjext31 = $.ajax(
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
				var ajaxresponse = response.split('^');  //alert(response.responseText())
				if(ajaxresponse[0] == '1')
				{
					msg_box.html(successmessage(ajaxresponse[1])) ;
					gridlist();
					griddata()
				}
				else if(ajaxresponse[0] == '2')
				{
					msg_box.html(errormessage(ajaxresponse[1]));
					gridlist();
					griddata()
				}
				else
				{
					msg_box.html(scripterror()) ;
				}
			}
		}, 
		error: function(a,b)
		{
		$("#gridprocess").html(scripterror1()) ;
		}
	});	
}

function load_state()
{
	var passdata = "&submittype=state&form_dlrlist=" + encodeURIComponent($("#form_dlrlist").val());
	//alert(passdata);
	var outputselect = $("#form_state");
	var queryString = "../ajax/statetransfermapping.php";
	ajaxobjext38 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
		
			outputselect.html(response);
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});
}

function divenable(hid, sho) 
{
	var dis = (sho.checked) ? "none" : "block";
	document.getElementById(hid).style.display = dis;
}

function newentry()
{
	//document.getElementById("msg_box").innerHTML = '';
	//$("#mastersearchform")[0].reset();
	window.location = '../mapping/statetransfer.php';
}