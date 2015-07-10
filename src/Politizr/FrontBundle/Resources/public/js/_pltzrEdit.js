$(function(){
	// edit summary
	var summaryEditor = new MediumEditor('.editable.summary', {   
		buttons: ['bold', 'italic', 'header1', 'header2', 'unorderedlist', 'quote', 'anchor'],
        buttonLabels: {
	        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
            'italic': '<span style=\"font-family: \'Merriweather Bold Italic\'\">i</span>',
	        'header1': '<span style=\"font-family: \'Merriweather\'\">H1</span>',
            'header2': '<span style=\"font-family: \'Merriweather\'\">H2</span>',
            'unorderedlist': '<i class="iconTimeline"></i>',
            'quote': '<i class="iconQuote"></i>',
            'anchor': '<i class="iconLink"></i>'
        },
	    placeholder: 'Cliquez ici pour saisir l\'accroche de votre publication.',
	    anchor: {
            placeholderText: 'Saississez une adresse internet'
        },
        anchorPreview: false,
        firstHeader:'h1',
        secondHeader:'h2'
    });
	// edit paragraph
	var descriptionEditor = new MediumEditor('.editable', {    
		buttons: ['bold', 'italic', 'header1', 'header2', 'unorderedlist', 'quote', 'anchor'],
        buttonLabels: {
	        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
            'italic': '<span style=\"font-family: \'Merriweather Bold Italic\'\">i</span>',
	        'header1': '<span style=\"font-family: \'Merriweather\'\">H1</span>',
            'header2': '<span style=\"font-family: \'Merriweather\'\">H2</span>',
            'unorderedlist': '<i class="iconTimeline"></i>',
            'quote': '<i class="iconQuote"></i>',
            'anchor': '<i class="iconLink"></i>'
        },
	    placeholder: 'Cliquez ici pour saisir le texte de votre publication.',
	    anchor: {
            placeholderText: 'Saississez une adresse internet'
        },
        anchorPreview: false,
        firstHeader:'h1',
        secondHeader:'h2'
    });	    
	
	var copyrightEditor = new MediumEditor('.editableTextarea', {
		buttons: ['bold', 'italic', 'anchor'],
        buttonLabels: {
	        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
            'italic': '<span style=\"font-family: \'Merriweather Bold Italic\'\">i</span>',
            'anchor': '<i class="iconLink"></i>'
        },
	    placeholder: 'Crédits photo',
	    anchor: {
            placeholderText: 'Saississez une adresse internet'
        },
        anchorPreview: false,
        firstHeader:'h1',
        secondHeader:'h2'
    });
    
    // compared mode
    $("body").on("click", "[action='activeComparedEdition']", function() {
		$('#swithEditionIndependentEdition').hide();
		$('#swithEditionComparedEdition').show();
		$('#postText').animate({width: "500px"}, 300);
		$('#postText .paragraph').animate({width: "340px"}, 300);
		$('#originalText').fadeIn(800);
	});
	$("body").on("click", "[action='activeIndenpendentEdition']", function() {
		$('#swithEditionIndependentEdition').show();
		$('#swithEditionComparedEdition').hide();
		$('#originalText').fadeOut();
		$('#postText').animate({width: "700px"}, 300);
		$('#postText .paragraph').animate({width: "540px"}, 300);
	});
	
	// edit title : auto resize text area
	$('.postSummaryFooter, #postText').on( 'change keyup keydown paste cut', 'textarea', function (){
		$(this).height(0).height(this.scrollHeight);					
	}).find( 'textarea' ).change();
	
	// open upload modal
	$("body").on("click", "[action='modalUploadOpen']", function() {
		$('#modalUploadBox').show();
		$('#modalUpload').slideDown('fast');
		$('body').addClass('noscroll');
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 0);
	});
	// close upload modal 
	$("body").on("click", "[action='modalUploadClose']", function() {
		$('body').removeClass('noscroll');
		$('#modalUploadBox').hide();
		$('#modalUpload').slideUp();
	});
	
	// close upload modal 
	$("body").on("click", "[action='maskIllustrationShadow']", function() {
		$('.maskIllustrationShadow, #currentPhoto .illustrationShadow').hide();
		$('.showIllustrationShadow').show();
	});
	$("body").on("click", "[action='showIllustrationShadow']", function() {
		$('.showIllustrationShadow').hide();
		$('.maskIllustrationShadow, #currentPhoto .illustrationShadow').show();
	});
	
	// toggle image copyright
	$("body").on("click", "[action='showCopyright']", function() {
		$('#copyright').toggle();		
	});
	
	// toggle helpers
	$("body").on("click", "[action='toggleHelper']", function() {
		$('.helperSlider').hide();
		$('.iconQuestion').css("color", "#b8b9bc");
		$(this).next('.helperSlider').toggle();
		$(this).css("color", "#079db5");
	});
	$("body").on("click", "[action='hideHelper']", function() {
		$('.helperSlider').hide();
		$('.iconQuestion').css("color", "#b8b9bc");
	});	
});	






