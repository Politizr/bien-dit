$(function(){
	// reputation chart	
	$("body").on("click", "#profileReputationCounter", function() {	
		// for html demo
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigners, #modalBoxContent.subscriptions, #modalBoxContent.followers, #modalBoxContent.myfollowers').hide();
		$('#modalBoxContent.reputation').fadeIn('fast');
		// end for html demo
			
		var reputationLineChartData = {
			labels : ["", "08/02", "09/02", "10/02", "11/02", "12/02", "13/02", "14/02", "15/02", "16/02", "17/02", "18/02", "19/02", "20/02", "21/02", "22/02", "23/02", "24/02", "25/02", "26/02", "27/02", "28/02", "01/03", "02/03", "03/03", "04/03", "05/03", "06/03", ""],
			datasets : [
				{
					label: "Reputation points",
					fillColor : "rgba(229, 108, 64, 0.3)",
					strokeColor : "#e56c40",
					pointColor : "#e56c40",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "#e56c40",
					data : [45, 65, 59, 80, 81, 56, 55, 40, 140, 220, 240, 240, 240, 240, 120, 120, 120, -60, -30, 80, 80, 60, 60, 60, 60, 80, 65, 65, 59]
				}
			]				
		}
		var ctx = document.getElementById("reputationLine").getContext("2d");
		window.myLine = new Chart(ctx).Line(reputationLineChartData, {
			responsive: true,
			animation: false,
			bezierCurve : false,
			scaleFontFamily: "'nerissemibold'",
			scaleFontSize: 8,
			scaleFontColor: "#a9a9a9",
			tooltipTemplate: "<%= value %> POINTS",
			tooltipFontFamily: "'nerisblack'",
			tooltipFontSize: 10,
			tooltipFillColor: "#2d2d2d",
			tooltipFontColor: "#fff",
			tooltipCornerRadius : 3,
			tooltipYPadding:10,
			tooltipXPadding:10,
			tooltipCaretSize:4,
			pointDotRadius : 4,
			pointDotStrokeWidth : 2,
			scaleShowHorizontalLines: false,
			scaleShowVerticalLines: false,
			scaleGridLineColor : "#dbdcdd",
			scaleLineColor: "#dbdcdd",
			datasetStrokeWidth : 2,
			pointHitDetectionRadius : 5
		});
	});		
	// end reputation chart
	
		// my followers chart	
		$("body").on("click", "#profileMyFollowersCounter", function() {	
		// for html demo
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigners, #modalBoxContent.subscriptions, #modalBoxContent.followers, #modalBoxContent.reputation').hide();
		$('#modalBoxContent.myfollowers').fadeIn('fast');
		// end for html demo
			
		var myFollowersLineChartData = {
			labels : ["", "08/02", "09/02", "10/02", "11/02", "12/02", "13/02", "14/02", "15/02", "16/02", "17/02", "18/02", "19/02", "20/02", "21/02", "22/02", "23/02", "24/02", "25/02", "26/02", "27/02", "28/02", "01/03", "02/03", "03/03", "04/03", "05/03", "06/03", ""],
			datasets : [
				{
					label: "Total Followers",
					fillColor : "rgba(229, 108, 64, 0.3)",
					strokeColor : "#e56c40",
					pointColor : "#e56c40",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "#e56c40",
					data : [45, 65, 59, 140, 220, 240, 240, 200, 220, 230, 242, 246, 242, 222, 125, 120, 100, 110, 84, 80, 102, 104, 104, 106, 110, 99, 165, 159, 154]
					
				},
				{
					label: "Followers Debattants",
					fillColor : "rgba(229, 108, 64, 0.3)",
					strokeColor : "#cb8f7a",
					pointColor : "#cb8f7a",
					pointStrokeColor : "#f7d3c6",
					pointHighlightFill : "#f7d3c6",
					pointHighlightStroke : "#cb8f7a",
					data : [0, 0, 2, 40, 81, 56, 55, 40, 45, 20, 30, 40, 41, 44, 68, 80, 40, 60, 50, 58, 56, 63, 54, 55, 57, 70, 65, 65, 59]
				}
			]				
		}
		var ctx = document.getElementById("myFollowersLine").getContext("2d");
		window.myLine = new Chart(ctx).Line(myFollowersLineChartData, {
			responsive: true,
			animation: false,
			bezierCurve : false,
			scaleFontFamily: "'nerissemibold'",
			scaleFontSize: 8,
			scaleFontColor: "#a9a9a9",
			tooltipFontFamily: "'nerisblack'",
			tooltipTitleFontFamily: "'nerisblack'",
			tooltipFontSize: 10,
			tooltipFontColor: "#fff",
			tooltipTitleFontColor: "#fff",
			tooltipTitleFontStyle: "normal",
			tooltipTitleFontSize: 10,
			tooltipFillColor: "#2d2d2d",
			tooltipCornerRadius : 3,
			tooltipYPadding:10,
			tooltipXPadding:10,
			tooltipCaretSize:4,
			pointDotRadius : 4,
			pointDotStrokeWidth : 2,
			scaleShowGridLines : false,
			scaleGridLineColor : "#dbdcdd",
			scaleLineColor: "#dbdcdd",
			datasetStrokeWidth : 2,
			pointHitDetectionRadius : 5
		});
		// end my reputation chart
		
		// my followers gender
		var genderData = [
			{
				value: 45,
				label: "Femme",
				color:"#b99abf",
				highlight: "#ccadd2"
			},
			{
				value: 55,
				label: "Homme",
				color:"#94b1b4",
				highlight: "#99c1c5"
			}
		];
		var ctx = document.getElementById("myFollowersGender").getContext("2d");
		window.myDoughnut = new Chart(ctx).Doughnut(genderData, {
			responsive : true,
			animation: false,
			tooltipFontFamily: "'nerisblack'",
			tooltipFontSize: 10,
			tooltipFontColor: "#fff",
			tooltipFillColor: "#2d2d2d",
			tooltipCornerRadius : 3,
			tooltipYPadding:10,
			tooltipXPadding:10,
			tooltipCaretSize:4,
			tooltipTemplate: "<%= value %>"
		});		
		// end my followers gender
		
		// my followers age
		var barChartData = {
			labels : ["18-24","25-34","35-44","45-54","55-64","65-74","75+"],
			datasets : [
				{
					fillColor : "#b99abf",
					highlightFill: "#ccadd2",
					data : [34, 54, 45, 65, 32, 45, 10]
				},
				{
					fillColor : "#94b1b4",
					highlightFill : "#99c1c5",
					data : [10, 17, 32, 27, 45, 56, 34]
				}
			]
		}	
		var ctx = document.getElementById("myFollowersAge").getContext("2d");
			window.myBar = new Chart(ctx).Bar(barChartData, {
				responsive : true,
				animation: false,
				scaleShowGridLines : false,
				scaleShowLabels: false,
				scaleFontFamily: "'nerissemibold'",
				scaleFontSize: 10,
				scaleFontColor: "#a9a9a9",
				scaleLineColor: "#fff",
				tooltipFontFamily: "'nerisblack'",
				tooltipTitleFontFamily: "'nerisblack'",
				tooltipFontSize: 10,
				tooltipFontColor: "#fff",
				tooltipTitleFontColor: "#fff",
				tooltipTitleFontStyle: "normal",
				tooltipTitleFontSize: 10,
				tooltipFillColor: "#2d2d2d",
				tooltipCornerRadius : 3,
				tooltipYPadding:10,
				tooltipXPadding:10,
				tooltipCaretSize:4,
				barStrokeWidth : 0,
				barValueSpacing : 9
			});	
		});		
		// end my followers age
});