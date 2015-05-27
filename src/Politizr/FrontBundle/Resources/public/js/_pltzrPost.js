$(function(){	
	// open/close comments in text body
    $("body").on("click", "[action='paragraphCommentsCounter']", function() {
	    
		if( $(this).siblings('.comments').is(':visible') ) {
			$(this).siblings('.comments').hide();
			$('.commentLevel2.commentsEditNew').hide();	
			}
		else {
			$('.comments').hide();
			$('.commentLevel2.commentsEditNew').hide();	
			$(this).siblings('.comments').show();	
			/* form new comment : auto resize text area */										
			$('.comments').on( 'change keyup keydown paste cut', 'textarea', function (){
			    $(this).height(0).height(this.scrollHeight);					
			}).find( 'textarea' ).change();	
		}
						
		// close comments
		$("body").on("click", "[action='paragraphCommentsClose']", function() {
			$('.comments').hide();	
			$('.commentLevel2.commentsEditNew').hide();						
		});
	});
	
	// toggle new comment form 
	$("body").on("click", "[action='commentsSlideFormNew']", function() {
		$(this).next('.commentsEditNew').toggle();
		/* form new comment : auto resize text area */	
		$('.comments').on( 'change keyup keydown paste cut', 'textarea', function (){
		    $(this).height(0).height(this.scrollHeight);					
		}).find( 'textarea' ).change();	
	});	
	
	// focus in textarea only for browser, crash fixed elements in mobile
	$("body.css, body.css1000").on("click", "[action='commentsSlideFormNew']", function() {
		$(this).next('.commentsEditNew').children('form').children('.commentsTextarea').focus();		
	});	
	
	$("body.css, body.css1000").on("click", "[action='paragraphCommentsCounter']", function() {
		$(this).siblings('.comments').children('.commentsEditNew').children('form').children('.commentsTextarea').focus();			
	});	
	
	
	// toggle image copyright
	$("body").on("click", "[action='showCopyright']", function() {
		$('#copyright').toggle();		
	});						
});