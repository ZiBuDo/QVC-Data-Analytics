var q3 = 0;
function question3(){ //this will hide the other questions and show question 1 info
	hide_all();
	$("#loading").show();
	$("#q3").show();
	$("#mainq3").show();
	$("#3dq3").show();
	$("#q3info").show();
	$("#none").hide();
	if(q3 == 0){
	var fitted = fitData(reg3);
	var slope3 = (fitted.slope).toFixed(2);
	var inter3 = (fitted.intercept).toFixed(2);
	reg3.sort(function(a,b){
		return a[0] - b[0];
	});
	var linear_reg3 = (fitted.data).sort(function(a,b){
		return a[0] - b[0];
	});
	Highcharts.setOptions(theme2);
	$('#mainq3').highcharts({
        chart: {
                type: 'scatter',
                zoomType: 'xy'
            },
            title: {
                text: 'Airtime Efficiency Without Hosts'
            },
            subtitle: {
                text: "Averages calculated based on sales per day"
            },
            xAxis: {
                title: {
                    enabled: true,
                    text: 'Duration of Airtime'
                },
                startOnTick: true,
                endOnTick: true,
                showLastLabel: true
            },
            yAxis: {
                title: {
                    text: 'Above or Below Average Sales ($)'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                backgroundColor: '#FFFFFF',
                borderWidth: 1
            },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<b>{series.name}</b><br>',
                        pointFormat: '${point.x}, ${point.y} '
                    }
                }
            },
            series: [{
            type: 'line',
            name: 'Regression Line',
            data: linear_reg3,
            marker: {
                enabled: false
            },
            states: {
                hover: {
                    lineWidth: 0
                }
            },
			tooltip: {
                        headerFormat: '<b>{series.name}</b><br><i>y = ' + slope3 +'x + ' + inter3 +'</i><br>',
                        pointFormat: '({point.x}, {point.y:.2f})'
                    }
        }, {
            type: 'scatter',
            name: 'Observations',
            data: reg3,
            marker: {
                radius: 4
            }
        }]
    });
	Highcharts.setOptions(def);	
	$('#3dq3').highcharts({
		chart: {
                type: 'spline',
                zoomType: 'xy'
            },
		title: {
            text: 'Specific Host Efficiency Versus Airtime',
            x: -20 //center
        },
        subtitle: {
            text: 'How efficient is a host depending on the length of the airtime',
            x: -20
        },
        xAxis: {
           title:{
			   text: 'Duration of airtime'
		   }
        },
        yAxis: {
            title: {
                text: 'Above or Below Average Sales per Day'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
		tooltip: {
                        headerFormat: '<b>{series.name}</b>',
                        pointFormat: '({point.x} minutes, ${point.y:.2f})'
                    },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0,
			title:{
				text: "Select or Deselect"
			}
        },
        series: air
    });
		q3 = 1;
	}
	$("#loading").hide();
}