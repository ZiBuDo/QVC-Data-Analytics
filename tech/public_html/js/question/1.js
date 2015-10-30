var def = {
  colors: ['#3f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce', '#492970',
        '#f28f43', '#77a1e5', '#c42525', '#a6c96a'],
   chart: {
      backgroundColor: '#fff',
      borderWidth: 0,
      plotBackgroundColor: '#fff',
      plotShadow: false,
      plotBorderWidth: 0
   },
   title: {
      style: {
            color: '#274b6d',//#3E576F',
            fontSize: '32px'
      }
   },
   subtitle: {
      style: {
            color: '#4d759e'
       }
   },
   xAxis: {
      gridLineWidth: 0,
      lineColor: '#C0D0E0',
      tickColor: '#C0D0E0',
      labels: {
         style: {
            color: '#666',
            cursor: 'default',
            fontSize: '11px',
            lineHeight: '14px'
         }
      },
      title: {
         style: {
                color: '#4d759e',
                fontWeight: 'bold'
        }
      }
   },
   yAxis: {
      minorTickInterval: null,
      lineColor: '#C0D0E0',
      lineWidth: 1,
      tickWidth: 1,
      tickColor: '#C0D0E0',
      labels: {
         style: {
            color: '#666',
            cursor: 'default',
            fontSize: '11px',
            lineHeight: '14px'
         }
      },
      title: {
         style: {
                color: '#4d759e',
                fontWeight: 'bold'
        }
      }
   },
   legend: {
      itemStyle: {
            color: '#274b6d',
            fontSize: '12px'
      },
      itemHoverStyle: {
         color: '#000'
      },
      itemHiddenStyle: {
         color: '#CCC'
      }
   },
   labels: {
      style: {
            color: '#3E576F'
        }
   },

   navigation: {
      buttonOptions: {
         theme: {
            stroke: '#CCCCCC'
         }
      }
   }
};
var theme2 = {
   colors: ["#f45b5b", "#8085e9", "#8d4654", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
      "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
   chart: {
      backgroundColor: null,
      style: {
         fontFamily: "Signika, serif"
      }
   },
   title: {
      style: {
         color: 'black',
         fontSize: '32px',
         fontWeight: 'bold'
      }
   },
   subtitle: {
      style: {
         color: 'black'
      }
   },
   tooltip: {
      borderWidth: 0
   },
   legend: {
      itemStyle: {
         fontWeight: 'bold',
         fontSize: '13px'
      }
   },
   xAxis: {
      labels: {
         style: {
            color: '#6e6e70'
         }
      }
   },
   yAxis: {
      labels: {
         style: {
            color: '#6e6e70'
         }
      }
   },
   plotOptions: {
      series: {
         shadow: true
      },
      candlestick: {
         lineColor: '#404048'
      },
      map: {
         shadow: false
      }
   },

   // Highstock specific
   navigator: {
      xAxis: {
         gridLineColor: '#D0D0D8'
      }
   },
   rangeSelector: {
      buttonTheme: {
         fill: 'white',
         stroke: '#C0C0C8',
         'stroke-width': 1,
         states: {
            select: {
               fill: '#D0D0D8'
            }
         }
      }
   },
   scrollbar: {
      trackBorderColor: '#C0C0C8'
   },

   // General
   background2: '#E0E0E8'
   
};
var q1 = 0;
function question1(){ //this will hide the other questions and show question 1 info
	hide_all();
	$("#loading").show();
	$("#main").show();
	$("#info").show();
	$("#state").show();
	$("#county").show();
	$("#total").show();
	$("#state2").show();
	$("#county2").show();
	$("#q1").show();
	$("#q1info").show();
	$("#none").hide();
	if(q1 == 0){
	var us = usa;
	var state_total;
	//var dist = county;
	var data = Highcharts.geojson(Highcharts.maps['countries/us/us-all']),
        // Some responsiveness
        small = $('#main').width() < 400;
    // Set drilldown pointers
    $.each(data, function () {
        this.drilldown = this.properties['hc-key'];
		for (var i = 0; i < us.length; i++) {
			if(us[i]["hc_key"] == this.properties['hc-key']){
				this.value = us[i].value;
				break;
			}
		}
    });
	var state, county, specounty;
	Highcharts.setOptions(def);
    // Instanciate the map
    $('#main').highcharts('Map', {
        chart : {
            events: {
                drilldown: function (e) {
					
                    if (!e.seriesOptions) {
                        var chart = this,
                            mapKey = 'countries/us/' + e.point.drilldown + '-all',
                            // Handle error, the timeout is cleared on success
                            fail = setTimeout(function () {
                                if (!Highcharts.maps[mapKey]) {
                                    chart.showLoading('<i class="icon-frown"></i> Failed loading ' + e.point.name);

                                    fail = setTimeout(function () {
                                        chart.hideLoading();
                                    }, 1000);
                                }
                            }, 3000);

                        // Show the spinner
                        chart.showLoading('<i class="icon-spinner icon-spin icon-3x"></i>'); // Font Awesome spinner

                        // Load the drilldown map
                        $.getScript('http://code.highcharts.com/mapdata/' + mapKey + '.js', function () {

                            data = Highcharts.geojson(Highcharts.maps[mapKey]);
                            // find the state and the county array
							for (var i = 0; i < us.length; i++) {
								if(us[i]["hc_key"] == e.point.drilldown){
									specounty = us[i].county;
									state_total = us[i].value;
									break;
								}
							}
							$.each(data, function (i) {
                                for (var i = 0; i < specounty.length; i++) {
									if(specounty[i].fips == this.properties.fips){
										this.value = (specounty[i].value);
										break;
									}
								}
                            });

                            // Hide loading and add series
                            chart.hideLoading();
                            clearTimeout(fail);
                            chart.addSeriesAsDrilldown(e.point, {
                                name: e.point.name,
                                data: data,
								cursor: 'pointer',
								point: {
									events: {
										click: function(event) {
											this.select();
											var cate, produ;
											for (var i = 0; i < specounty.length; i++) {
												if(specounty[i].fips == this.properties.fips){
													cate = specounty[i].categories;
													produ = specounty[i].products;
													break;
												}
											}
											county.destroy();
											$('#county').highcharts({
												chart: {
													type: 'pie',
													spacingTop: 0,
													spacingRight: 0,
													spacingBottom: 3,
													spacingLeft: 4
												},
												title: {
													text: this.name + " Product Categories"
												},
												subtitle: {
													text: 'Click on slice to view specific products.'
												},
												plotOptions: {
													series: {
														cursor: 'pointer',
														size: "75%",
															point: {
																events: {
																	click: function (e) {
																		if(e.point.name != null){
																			hs.htmlExpand(null, {
																				pageOrigin: {
																					x: e.pageX || e.clientX,
																					y: e.pageY || e.clientY
																				},
																				headingText: e.point.name,
																				maincontentText: e.point.info,
																				width: 200
																			});
																		}
																	}
																}
															},
														dataLabels: {
															enabled: true,
															format: '{point.name}: {point.y:.3f}%'
														}
													}
												},

												tooltip: {
													headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
													pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.3ff}%</b> of total<br/>'
												},
												series: [{
													name: "Categories",
													colorByPoint: true,
													data: cate
												}],
												drilldown: {
													series: produ
												}
											});
											county = $('#county').highcharts();
											
										}
									}
								},
                                dataLabels: {
									allowPointSelect: true,
                                    enabled: true,
                                    format: '{point.name}'
                                }
                            });
                        });
                    }


                    this.setTitle(null, { text: e.point.name });
					for (var i = 0; i < us.length; i++) {
						if(us[i]["hc_key"] == e.point.drilldown){
							Highcharts.setOptions(def);
							state.destroy();
							$('#state').highcharts({
								chart: {
									type: 'pie',
									spacingTop: 0,
									spacingRight: 0,
									spacingBottom: 3,
									spacingLeft: 4
								},
								title: {
									text: e.point.name + " Product Categories"
								},
								subtitle: {
									text: 'Click on slice to view specific products.'
								},
								plotOptions: {
									series: {
										cursor: 'pointer',
										size: "75%",
											point: {
												events: {
													click: function (e) {
														if(e.point.name != null){
															hs.htmlExpand(null, {
																pageOrigin: {
																	x: e.pageX || e.clientX,
																	y: e.pageY || e.clientY
																},
																headingText: e.point.name,
																maincontentText: e.point.info,
																width: 200
															});
														}
													}
												}
											},
										dataLabels: {
											enabled: true,
											format: '{point.name}: {point.y:.3f}%'
										}
									}
								},

								tooltip: {
									headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
									pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.3f}%</b> of total<br/>'
								},
								series: [{
									name: "Categories",
									colorByPoint: true,
									data: us[i].categories
								}],
								drilldown: {
									series: us[i].products
								}
							});
							state = $('#state').highcharts();
							break;
						}
					}
                },
                drillup: function () {
                    this.setTitle(null, { text: 'USA' });
                }
            }
        },

        title : {
            text : 'QVC Products Total Sales'
        },

        subtitle: {
            text: 'USA',
            floating: true,
            align: 'right',
            y: 50,
            style: {
                fontSize: '16px'
            }
        },

        legend: small ? {} : {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        colorAxis: {
            min: null,
            minColor: '#ADFFB3',
            maxColor: '#006B07'
        },

        mapNavigation: {
            enabled: true,
            buttonOptions: {
                verticalAlign: 'bottom'
            }
        },

        plotOptions: {
            map: {
                states: {
                    hover: {
                        color: '#EEDD66'
                    }
                }
            }
        },

        series : [{
            data : data,
            name: 'USA',
            dataLabels: {
                enabled: true,
                format: '{point.properties.postal-code}'
            }
        }],

        drilldown: {
            //series: drilldownSeries,
            activeDataLabelStyle: {
                color: '#FFFFFF',
                textDecoration: 'none',
                textShadow: '0 0 3px #000000'
            },
            drillUpButton: {
                relativeTo: 'spacingBox',
                position: {
                    x: 0,
                    y: 60
                }
            }
        }
		
    });
	$('#state').highcharts({
		chart: {
			type: 'pie',
			spacingTop: 0,
            spacingRight: 0,
            spacingBottom: 3,
            spacingLeft: 4
		},
		title: {
			text: 'Pennsylvania Product Categories'
		},
		subtitle: {
			text: 'Click on slice to view specific products.'
		},
		plotOptions: {
			series: {
				cursor: 'pointer',
				size: "75%",
                    point: {
                        events: {
                            click: function (e) {
                                if(e.point.name != null){
									hs.htmlExpand(null, {
										pageOrigin: {
											x: e.pageX || e.clientX,
											y: e.pageY || e.clientY
										},
										headingText: e.point.name,
										maincontentText: e.point.info,
										width: 200
									});
								}
                            }
                        }
                    },
				dataLabels: {
					enabled: true,
					format: '{point.name}: {point.y:.3f}%'
				}
			}
		},

		tooltip: {
			headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.3f}%</b> of total<br/>'
		},
		series: [{
			name: "Categories",
			colorByPoint: true,
			data: usa[36].categories
		}],
		drilldown: {
			series: usa[36].products
		}
	});
	state = $('#state').highcharts();
	
	$('#county').highcharts({
		chart: {
			type: 'pie',
			spacingTop: 0,
            spacingRight: 0,
            spacingBottom: 3,
            spacingLeft: 4
		},
		title: {
			text: 'Bucks Product Categories'
		},
		subtitle: {
			text: 'Click on slice to view specific products.'
		},
		plotOptions: {
			series: {
				cursor: 'pointer',
				size: "75%",
                    point: {
                        events: {
                            click: function (e) {
                                if(e.point.name != null){
									hs.htmlExpand(null, {
										pageOrigin: {
											x: e.pageX || e.clientX,
											y: e.pageY || e.clientY
										},
										headingText: e.point.name,
										maincontentText: e.point.info,
										width: 200
									});
								}
                            }
                        }
                    },
				dataLabels: {
					enabled: true,
					format: '{point.name}: {point.y:.3f}%'
				}
			}
		},

		tooltip: {
			headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.3f}%</b> of total<br/>'
		},
		series: [{
			name: "Categories",
			colorByPoint: true,
			data: usa[36].county[8].categories
		}],
		drilldown: {
			series: usa[36].county[8].products
		}
	});
	county = $('#county').highcharts();
	
Highcharts.setOptions(theme2);
	$('#total').highcharts({
		
		chart: {
			type: 'pie',
			spacingTop: 0,
            spacingRight: 0,
            spacingBottom: 3,
            spacingLeft: 4
		},
		title: {
			text: 'United States Best Sellers<br><i>Total Sales: $340,891,894</i>'
		},
		subtitle: {
			text: 'Click on slice to view specific products.'
		},
		plotOptions: {
			series: {
				cursor: 'pointer',
				size: "90%",
                    point: {
                        events: {
                            click: function (e) {
                                if(e.point.name != null){
									hs.htmlExpand(null, {
										pageOrigin: {
											x: e.pageX || e.clientX,
											y: e.pageY || e.clientY
										},
										headingText: e.point.name,
										maincontentText: e.point.info,
										width: 200
									});
								}
                            }
                        }
                    },
				dataLabels: {
					enabled: true,
					format: '{point.name}: {point.y:.3f}%',
					style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
				}
			}
		},

		tooltip: {
			headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.3f}%</b> of total<br/>'
		},
		series: [{
			name: "Categories",
			colorByPoint: true,
			data: total.categories
		}],
		drilldown: {
			series: total.products
		}
	});	
	Highcharts.setOptions(def);
		q1 = 1;
	}
	$("#loading").hide();
}

