function retrievedata()
{
	$('#yaxisscale').val('') ;
	$('#hiddenvalue').val('');
	$('#xaxisscale').val('');
	$('#container').html(processing());
	var type = $('#period').val(); //alert(type);
	var source = $('#source').val();
	var area = $('#area').val();
	var productgroup = $('#productgroup').val();
	var passdata = "&submittype="+type+"&source="+source+"&area="+area+"&productgroup="+productgroup+"&dummy=" + Math.floor(Math.random()*100032680100); //alert(passdata)
	var queryString = "../ajax/graphdatadealer.php";
	ajaxobjext49 = $.ajax(
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
				if(type == 'thismonth'  || type == 'lastmonth')
				{
					var splitval = response.split('###');
					var splitwithcomma = splitval[1].split(','); 
					var maxvalue = Math.max.apply( Math, splitwithcomma ); //alert(maxvalue)
					var minvalue = Math.min.apply( Math,splitwithcomma );  //alert(minvalue)
					$('#hiddenvalue').val(splitval[1]); //alert($('#hiddenvalue').val())
					$('#yaxisscale').val(maxvalue); 
					$('#xaxisscale').val(minvalue); 
					$('#alldates').val(splitval[0]); //alert($('#alldates').val())
					displaygraph(type);
				}
				else
				{
					var splitval = response.split(',');
					var maxvalue = Math.max.apply( Math, splitval ); 
					var minvalue = Math.min.apply( Math, splitval ); 
					$('#hiddenvalue').val(response);
					$('#yaxisscale').val(maxvalue);
					$('#xaxisscale').val(minvalue);
					displaygraph(type);
				}
			}
		}, 
		error: function(a,b)
		{
			messagebox.html(scripterror());
		}
	});		
}

function displaygraph(type) 
{
	
	switch(type)
	{
		case "lastfinancialyear":
				var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			defaultSeriesType: 'line'
		},
		title: {
			text: ''
		},
	
		xAxis: {
			categories: [ 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar']
		},
		yAxis: {
			min : $('#xaxisscale').val(),
			max : $('#yaxisscale').val(),
			title: {
				text: 'Total Number of Leads ',
				align: 'middle'
			},
			plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.x +'</b><br/>'+
				this.series.name +': '+ this.y ;
			}
		},
		
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		},
		
		series: [{
			name: 'Leads',
			type: 'line',
			data: (function() {
							var data = [];
							value = $('#hiddenvalue').val();
							//alert(value)
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							} //alert(data)
							return  data;})()
						
		}]
		});
			break;
	
		case "thisfinancialyear":
		var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			defaultSeriesType: 'line'
		},
		title: {
			text: ''
		},
	
		xAxis: {
			categories: [ 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar']
		},
		yAxis: {
			min : $('#xaxisscale').val(),
			max : $('#yaxisscale').val(),
			title: {
				text: 'Total Number of Leads ',
				align: 'middle'
			}
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.x +'</b><br/>'+
				this.series.name +': '+ this.y ;
			}
		},
			plotOptions: {
						line: {
							dataLabels: {
								enabled: false
							},
							enableMouseTracking: true
						}
					},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		},
	
		series: [{
			name: 'Leads',
			type: 'line',
			data: (function() {
							var data = [];
							value = $('#hiddenvalue').val();
							//alert(value)
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
							return  data;})()
						
		}]
		});
			break;
		
		case "thismonth":
			var datevalues = $('#alldates').val();
			var splitdates = datevalues.split(','); //alert(splitdates)
		
			var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			defaultSeriesType: 'line'
		},
		title: {
			text: ''
		},
	
		xAxis: {
			categories: splitdates,
			minPadding: 0.2,
				title:
				{
					text: 'Number of Days',
					align: 'middle'
				}
		},
		yAxis: {
			min : $('#xaxisscale').val(),
			max : $('#yaxisscale').val(),
			title: {
				text: 'Total Number of Leads ',
				align: 'middle'
			}
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.x +'</b><br/>'+
				this.series.name +': '+ this.y ;
			}
		},
		
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		},
	
		series: [{
			name: 'Leads',
			type: 'line',
			data: (function() {
							var data = [];
							value = $('#hiddenvalue').val();
							//alert(value)
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							} 
							return  data;})()
						
		}]
		});
		
			break;
			
		case "lastmonth":
			var datevalues = $('#alldates').val();
			var splitdates = datevalues.split(','); //alert(splitdates)
			var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			defaultSeriesType: 'line'
		},
		title: {
			text: ''
		},
	
		xAxis: {
			categories: splitdates,
				title:
				{
					text: 'Number of Days',
					align: 'middle'
				}
		},
		yAxis: {
			min : $('#xaxisscale').val(),
			max : $('#yaxisscale').val(),
			title: {
				text: 'Total Number of Leads ',
				align: 'middle'
			}
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.x +'</b><br/>'+
				this.series.name +': '+ this.y ;
			}
		},
		
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		},
	
		series: [{
			name: 'Leads',
			type: 'line',
			data: (function() {
							var data = [];
							value = $('#hiddenvalue').val();
							//alert(value)
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
							return  data;})()
						
		}]
		});
			break;
	}
}

			
