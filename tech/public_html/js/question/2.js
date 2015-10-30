
	
var q2 = 0;
function question2(){ //this will hide the other questions and show question 1 info
	hide_all();
	$("#loading").show();
	$("#mainq2").show();
	$("#q2").show();
	$("#statq2").show();
	$("#q2point").show();
	$("#infoq2").show();
	$("#q2info").show();
	$("#none").hide();
	if(q2 == 0){
	var slope = (fitData(reg).slope).toFixed(2);
	var inter = (fitData(reg).intercept).toFixed(2);
	var specpoint;
	reg.sort(function(a,b){
		return a[0] - b[0];
	});
	var linear_reg = (fitData(reg).data).sort(function(a,b){
		return a[0] - b[0];
	});
	Highcharts.setOptions(def);
	$('#mainq2').highcharts('StockChart', {
            rangeSelector : {
                selected : 0
            },

            title : {
                text : 'Sales with Email Campaign Events',
				style: {
					color: '#274b6d',//#3E576F',
					fontSize: '32px'
			  }
            },

            tooltip: {
                style: {
                    width: '200px'
                },
                valueDecimals: 4,
                shared : true
            },
			legend: {
				enabled: true,
				align: 'right',
				backgroundColor: '#FCFFC5',
				borderColor: 'black',
				borderWidth: 2,
				layout: 'vertical',
				verticalAlign: 'top',
				y: 100,
				shadow: true,
				title:{
					text: "Select or Deselect"
				}
			},
            yAxis : {
                title : {
                    text : 'Sales ($)'
                }
            },
			plotOptions: {
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function (event) {
								specpoint.destroy();
								$('#q2point').highcharts({
									chart: {
										type: 'column'
									},
									title: {
										text: 'Point Compared to Average Sales'
									},
									subtitle: {
													text: this.series.name
									},
									xAxis: {
										categories: [
											'Average',
											new Date(this.x).toLocaleDateString()
										],
										crosshair: true
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Sales ($)'
										}
									},
									tooltip: {
										headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
										pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
											'<td style="padding:0"><b>${point.y:.2f}</b></td></tr>',
										footerFormat: '</table>',
										shared: true,
										useHTML: true
									},
									plotOptions: {
										column: {
											pointPadding: 0.2,
											borderWidth: 0
										}
									},
									series: [{
										name: 'Average',
										data: [this.series.options.avg, null]

									},{
										name: new Date(this.x).toLocaleDateString(),
										data: [null, this.y]

									}]
								});
								specpoint = $('#q2point').highcharts();
                               
                            }
                        }
                    },
                    marker: {
                        lineWidth: 1
                    }
                }
            },
            series : camp
        });
		Highcharts.setOptions(theme2);
		$('#statq2').highcharts({
        chart: {
                type: 'scatter',
                zoomType: 'xy'
            },
            title: {
                text: 'Campaign Spending Efficiency'
            },
            subtitle: {
                text: 'Averages calculated based on daily sales'
            },
            xAxis: {
                title: {
                    enabled: true,
                    text: 'Campaign Spending ($)'
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
            data: linear_reg,
            marker: {
                enabled: false
            },
            states: {
                hover: {
                    lineWidth: 0
                }
            },
			tooltip: {
                        headerFormat: '<b>{series.name}</b><br><i>y = ' + slope +'x + ' + inter +'</i><br>',
                        pointFormat: '({point.x}, {point.y:.2f})'
                    }
        }, {
            type: 'scatter',
            name: 'Observations',
            data: reg,
            marker: {
                radius: 4
            }
        }]
    });
	$('#q2point').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Point Compared to Average Sales'
        },
		subtitle: {
            text: 'This is an example, select a point on the graph above.'
        },
        xAxis: {
            categories: [
                'Average',
                'Specific Point'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Sales ($)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>${point.y:.2f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Average',
            data: [49.9, null]

        },{
            name: 'Specific Point',
            data: [null, 87.5]

        }]
    });
	specpoint = $('#q2point').highcharts();
		q2 = 1;
	}
	$("#loading").hide();
}