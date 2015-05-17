$(function(){		
	// open modal with content								
	$("body").on("click", "#linkRanking", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search').hide();
		$('#modalBoxContent.ranking').fadeIn('fast');
	});
	
	$("body").on("click", "#linkSuggestions", function() {
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search').hide();
		$('#modalBoxContent.suggestions').fadeIn('fast');
	});
	
	$("body").on("click", ".openListByTag", function() {
		$('##modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.ranking, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search').hide();
		$('#modalBoxContent.listByTag').fadeIn('fast');
	});
	
	$("body").on("click", ".organization", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.ranking, #modalBoxContent.formAbuse, #modalBoxContent.search').hide();
		$('#modalBoxContent.organizationSheet').fadeIn('fast');
	});
	
	$("body").on("click", ".signalAbuse", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.ranking, #modalBoxContent.search').hide();
		$('#modalBoxContent.formAbuse').fadeIn('fast');
	});
	
	$("body").on("click", ".openSearch", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.ranking').hide();
		$('#modalBoxContent.search').fadeIn('fast');
	});
	// end open modal with content
	
	// criteria tabs
	
	$("body").on("click", "input#criteriaProfile", function() {	    
		if( $('.postTab').is(':visible') ) {
			$('.postTab').hide();
			$('.profileTab').show();	
			}
		else {
			$('.profileTab').hide();
			$('.postTab').show();
		}
	});
	
	$("body").on("click", "input#criteriaPost", function() {	    
		if( $('.profileTab').is(':visible') ) {
			$('.profileTab').hide();
			$('.postTab').show();	
			}
		else {
			$('.postTab').hide();
			$('.profileTab').show();
		}
	});
});
