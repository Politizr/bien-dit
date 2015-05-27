$(function() {
	// sticky timelineHeader for css
	$('body.css #timeline').stickyTimelineHeader({stickyClass : 'stickyTimelineHeader'});
	
	// sticky timeline dates for css
	$('body.css .timelineDayContainer').stickyDay({stickyClass : 'timelineDay'});
	// sticky timeline dates for css 1000
	$('body.css1000 .timelineDayContainer').stickyDay1000({stickyClass : 'timelineDay'});
	// sticky timeline dates for css 760
	$('body.css760 .timelineDayContainer').stickyDay760({stickyClass : 'timelineDay'});
	
	// scroll to .today
	$("body").on("click", "[action='goToToday']", function() {
		$('html, body').animate({
			scrollTop: $(".today").offset().top - 60 // minus 60px (sticky height)
		}, '2000');
	});
});