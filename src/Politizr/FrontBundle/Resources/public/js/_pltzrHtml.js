$(function(){		
	// open modal with content								
	$("body").on("click", "#linkRanking", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigner, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.ranking').fadeIn('fast');
	});
	
	$("body").on("click", "#linkSuggestions", function() {
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigner, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.suggestions').fadeIn('fast');
	});
	
	$("body").on("click", ".tag", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.ranking, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigner, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.listByTag').fadeIn('fast');
	});
	
	$("body").on("click", ".organization", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.ranking, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigner, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.organizationSheet').fadeIn('fast');
	});
	
	$("body").on("click", ".signalAbuse", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.ranking, #modalBoxContent.search, #modalBoxContent.cosigner, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.formAbuse').fadeIn('fast');
	});
	
	$("body").on("click", ".openSearch", function() {
		$('#modalBoxContent, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.ranking, #modalBoxContent.cosigner, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.search').fadeIn('fast');
	});
	
	$("body").on("click", ".postCosigners", function() {
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.subscriptions').hide();
		$('#modalBoxContent.cosigners').fadeIn('fast');
	});

	$("body").on("click", ".manageSubscriptions", function() {
		$('#modalBoxContent, #modalBoxContent.ranking, #modalBoxContent.suggestions, #modalBoxContent.listByTag, #modalBoxContent.organizationSheet, #modalBoxContent.formAbuse, #modalBoxContent.search, #modalBoxContent.cosigners').hide();
		$('#modalBoxContent.subscriptions').fadeIn('fast');
	});

	// end open modal with content
	
	// criteria tabs
	
	$("body").on("click", "input#criteriaProfile", function() {	    
		if( $('.postTab').is(':visible') ) {
			$('.postTab').hide();
			$('.profileTab').show();	
			}
		else {
		}
	});
	
	$("body").on("click", "input#criteriaPost", function() {	    
		if( $('.profileTab').is(':visible') ) {
			$('.profileTab').hide();
			$('.postTab').show();	
			}
		else {
		}
	});
});
