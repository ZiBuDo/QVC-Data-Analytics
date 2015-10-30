var q4 = 0;
function question4(){ //this will hide the other questions and show question 1 info
	hide_all();
	$("#loading").show();
	$("#q4").show();
	$("#mainq4").show();
	$("#forumq4").show();
	$("#fbq4").show();
	$("#twitterq4").show();
	$("#sentq4").show();
	$("#scatq4").show();
	$("#q4info").show();
	$("#none").hide();
	if(q4 == 0){
	sent.sentiment.sort(function(a,b){
		return a[0] - b[0];
	});
	sent.sales.sort(function(a,b){
		return a[0] - b[0];
	});
	sent.average.sort(function(a,b){
		return a[0] - b[0];
	});
	Highcharts.setOptions(def);
	$('#forumq4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Forum Sentiments'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
				size: "100%",
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: "Sentiment",
            colorByPoint: true,
            data: sent.forum
        }]
    });
	$('#twitterq4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Twitter Sentiments'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
				size: "100%",
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: "Sentiment",
            colorByPoint: true,
            data: sent.twitter
        }]
    });
	$('#fbq4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Facebook Sentiments'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
				size: "100%",
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: "Sentiment",
            colorByPoint: true,
            data: sent.fb
        }]
    });
	$('#mainq4').highcharts('StockChart', {
            rangeSelector: {
                selected: 1
            },

            title: {
                text: 'Sentiment versus Sales per 12 Hours'
            },

            yAxis: [{
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'Total Sales per 12 hours'
                },
                height: '60%',
                lineWidth: 2
            },{
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'Sentiment Score'
                },
				top: '60%',
                height: '40%',
                lineWidth: 2
            }],

            series: [{
                type: 'line',
                name: 'Sales per hour',
                data: sent.sales,
				yAxis: 0,
            }, {
                type: 'line',
                name: 'Average Sales per hour',
                data: sent.average,
                yAxis: 0
            },
			{
                type: 'column',
                name: 'Sentiment Score',
                data: sent.sentiment,
				yAxis: 1
            }]
        });
		
	var fitted4 = fitData(reg_sent);
	var slope4 = (fitted4.slope).toFixed(2);
	var inter4 = (fitted4.intercept).toFixed(2);
	reg_sent.sort(function(a,b){
		return a[0] - b[0];
	});
	var linear_reg4 = (fitted4.data).sort(function(a,b){
		return a[0] - b[0];
	});
	Highcharts.setOptions(theme2);
	$('#scatq4').highcharts({
        chart: {
                type: 'scatter',
                zoomType: 'xy'
            },
            title: {
                text: 'Sentiments Affecting Sales'
            },
            subtitle: {
                text: "Sentiments calculated with 12 hour lag time"
            },
            xAxis: {
                title: {
                    enabled: true,
                    text: 'Sentiment Score'
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
                        pointFormat: '{point.x}, ${point.y} '
                    }
                }
            },
            series: [{
            type: 'line',
            name: 'Regression Line',
            data: linear_reg4,
            marker: {
                enabled: false
            },
            states: {
                hover: {
                    lineWidth: 0
                }
            },
			tooltip: {
                        headerFormat: '<b>{series.name}</b><br><i>y = ' + slope4 +'x + ' + inter4 +'</i><br>',
                        pointFormat: '({point.x}, {point.y:.2f})'
                    }
        }, {
            type: 'scatter',
            name: 'Observations',
            data: reg_sent,
            marker: {
                radius: 4
            }
        }]
    });
		
		
			q4 = 1;
	}
		$("#loading").hide();
}