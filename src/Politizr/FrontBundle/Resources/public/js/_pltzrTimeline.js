$(function() {
	// sticky day for css
		// with timelineHeader
	if( $('body.css #timelineHeader').is(':visible') ) {
		$('body.css .timelineDayContainer').stickyDayWithTimelineHeader({stickyClass : 'timelineDay'});
		$('body.css #timeline').stickyTimelineHeader({stickyClass : 'stickyTimelineHeader'});
		}
		// without timelineHeader
	else {
		$('body.css .timelineDayContainer').stickyDayWithoutTimelineHeader({stickyClass : 'timelineDay'});
	}
	// sticky timeline dates for css 1000
	$('body.css1000 .timelineDayContainer').stickyDay1000({stickyClass : 'timelineDay'});
	// sticky timeline dates for css 760
	$('body.css760 .timelineDayContainer').stickyDay760({stickyClass : 'timelineDay'});
	
});

