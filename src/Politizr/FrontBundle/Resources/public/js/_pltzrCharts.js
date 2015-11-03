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
			scaleShowGridLines : false,
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
				barShowStroke : false,
				barStrokeWidth : 0,
				barValueSpacing : 9
			});	
		});		
		// end my followers age
		
	// post chart	
	$("body").on("click", ".publicationStats", function() {	
		// for html demo
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigners, #modalBoxContent.inviteCosigners, #modalBoxContent.subscriptions, #modalBoxContent.followers, #modalBoxContent.myfollowers').hide();
		$('#modalBoxContent.postStats').fadeIn('fast');
		// end for html demo
		
		// post note
		var myPostsNoteChartData = {
			labels : ["", "08/02", "09/02", "10/02", "11/02", "12/02", "13/02", "14/02", "15/02", "16/02", "17/02", "18/02", "19/02", "20/02", "21/02", "22/02", "23/02", "24/02", "25/02", "26/02", "27/02", "28/02", "01/03", "02/03", "03/03", "04/03", "05/03", "06/03", ""],
			datasets : [
				{
					label: "Note points",
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
		var ctx = document.getElementById("myPostsNote").getContext("2d");
		window.myLine = new Chart(ctx).Line(myPostsNoteChartData, {
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
			scaleShowGridLines : false,
			scaleGridLineColor : "#dbdcdd",
			scaleLineColor: "#dbdcdd",
			datasetStrokeWidth : 2,
			pointHitDetectionRadius : 5
		});
		// end post note
		
		// mypost visitors
		var myPostsVisitorsChartData = {
			labels : ["", "08/02", "09/02", "10/02", "11/02", "12/02", "13/02", "14/02", "15/02", "16/02", "17/02", "18/02", "19/02", "20/02", "21/02", "22/02", "23/02", "24/02", "25/02", "26/02", "27/02", "28/02", "01/03", "02/03", "03/03", "04/03", "05/03", "06/03", ""],
			datasets : [
				{
					label: "Note points",
					fillColor : "rgba(142, 177, 107, 0.4)",
					strokeColor : "#8eb16b",
					pointColor : "#8eb16b",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "#8eb16b",
					data : [45, 65, 69, 70, 71, 76, 85, 90, 140, 220, 240, 240, 240, 240, 250, 250, 250, 260, 270, 280, 280, 360, 360, 360, 360, 380, 385, 385, 390]
				}
			]				
		}
		var ctx = document.getElementById("myPostsVisitors").getContext("2d");
		window.myLine = new Chart(ctx).Line(myPostsVisitorsChartData, {
			responsive: true,
			animation: false,
			bezierCurve : false,
			scaleFontFamily: "'nerissemibold'",
			scaleFontSize: 8,
			scaleFontColor: "#a9a9a9",
			tooltipTemplate: "<%= value %> VISITES",
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
			scaleShowGridLines : false,
			scaleGridLineColor : "#dbdcdd",
			scaleLineColor: "#dbdcdd",
			datasetStrokeWidth : 2,
			pointHitDetectionRadius : 5
		});
		// end post note
		
		// post reactions
		var myPostsReactionsChartData = {
			labels : ["", "08/02", "09/02", "10/02", "11/02", "12/02", "13/02", "14/02", "15/02", "16/02", "17/02", "18/02", "19/02", "20/02", "21/02", "22/02", "23/02", "24/02", "25/02", "26/02", "27/02", "28/02", "01/03", "02/03", "03/03", "04/03", "05/03", "06/03", ""],
			datasets : [
				{
					label: "Reactions on post",
					fillColor : "#e56d41",
					highlightFill: "#ef9c7e",
					data : [0, 0, 0, 0, 0, 0, 2, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 4, 1, 2, 0, 0, 0, 0, 0]
				}
			]				
		}
		var ctx = document.getElementById("myPostsReactions").getContext("2d");
		window.myLine = new Chart(ctx).Bar(myPostsReactionsChartData, {
			responsive : true,
			animation: false,
			scaleShowGridLines : false,
			scaleFontFamily: "'nerissemibold'",
			scaleFontSize: 8,
			scaleFontColor: "#a9a9a9",
			tooltipTemplate: "<%= value %> RÉACTIONS",
			tooltipFontFamily: "'nerisblack'",
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
			barShowStroke : false,
			barStrokeWidth : 0
		});
		// end post reactions
		
		// post comments
		var myPostsCommentsChartData = {
			labels : ["", "08/02", "09/02", "10/02", "11/02", "12/02", "13/02", "14/02", "15/02", "16/02", "17/02", "18/02", "19/02", "20/02", "21/02", "22/02", "23/02", "24/02", "25/02", "26/02", "27/02", "28/02", "01/03", "02/03", "03/03", "04/03", "05/03", "06/03", ""],
			datasets : [
				{
					label: "Comments on post",
					fillColor : "#6ba8ad",
					highlightFill: "#83bcc1",
					data : [12, 0, 0, 15, 4, 23, 0, 15, 5, 28, 9, 4, 16, 0, 0, 0, 0, 13, 13, 28, 19, 4, 10, 0, 0, 0, 0, 11, 2]
				}
			]				
		}
		var ctx = document.getElementById("myPostsComments").getContext("2d");
		window.myLine = new Chart(ctx).Bar(myPostsCommentsChartData, {
			responsive : true,
			animation: false,
			scaleShowGridLines : false,
			scaleFontFamily: "'nerissemibold'",
			scaleFontSize: 8,
			scaleFontColor: "#a9a9a9",
			tooltipTemplate: "<%= value %> COMMENTAIRES",
			tooltipFontFamily: "'nerisblack'",
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
			barShowStroke : false,
			barStrokeWidth : 0
		});
		// end post comments
		
	});		
	// end mypost chart
});