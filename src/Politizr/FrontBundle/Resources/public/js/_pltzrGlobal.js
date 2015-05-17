$(function(){	
	// mobile : toggle menu mobile 
	$("body").on("touchstart click", "[action='menuMobileTriggerMenu']", function(e) {
		if(e.type == "touchstart") { // if touchstart start toggle
			$('#headerCenter, #menu').toggle();
			$('#menuPreferences, #fixedActions').hide();
			e.stopPropagation();
	        e.preventDefault(); // stop touchstart 
	        return false;
		} else if(e.type == "click") { // if click : do the same, but don't trigger touchstart
	        $('#headerCenter, #menu').toggle();
	        $('#menuPreferences, #fixedActions').hide();
	    }
	});
	
	// mobile : toggle menu preferences
	$("body.css760 #hideMenuPreferences").hide();
	$("body.css760").on("touchstart click", "[action='openMenuPreferences']", function(e) {
		if(e.type == "touchstart") { // if touchstart start toggle
			$('#menuPreferences').toggle();
			$('#headerCenter, #menu, #fixedActions').hide();
			e.stopPropagation();
	        e.preventDefault(); // stop touchstart 
	        return false;
		} else if(e.type == "click") { // if click : do the same, but don't trigger touchstart
			$('#menuPreferences').toggle();
			$('#headerCenter, #menu, #fixedActions').hide();				    
		}					
	});
	
	// mobile : toggle fixed actions 
	$("body.css760").on("touchstart click", "[action='menuMobileTriggerActions']", function(e) {
		if(e.type == "touchstart") { // if touchstart start toggle
			$('#fixedActions').toggle();
			$('#headerCenter, #menu, #menuPreferences').hide();
			e.stopPropagation();
	        e.preventDefault(); // stop touchstart 
	        return false;
		} else if(e.type == "click") { // if click : do the same, but don't trigger touchstart
			$('#fixedActions').toggle();
			$('#headerCenter, #menu, #menuPreferences').hide();	    
		}					
	});
	
	///// has been ///// mobile : gestion pseudo tabs in modal with two cols
	$("body.css760").on("touchstart click", "[action='mobileTriggerLeftCol']", function() {
		$(this).addClass('activeMobileModal');
		$(".modalRightCol").removeClass('activeMobileModal');
		$('.modal .wrapper').animate({
			scrollTop: $("body").offset().top
		}, 'fast');
	});
	
	$("body.css760").on("touchstart click", "[action='mobileTriggerRightCol']", function() {
		$(this).addClass('activeMobileModal');
		$(".modalLeftCol").removeClass('activeMobileModal');
		$('.modal .wrapper').animate({
			scrollTop: $("body").offset().top
		}, 'fast');
	});
	
	$("body.css760").on("touchstart click", ".modalRightCol.activeMobileModal", function() {
		$('.modal .wrapper').stop();
	});
	$("body.css760").on("touchstart click", ".modalLeftCol.activeMobileModal", function() {
		$('.modal .wrapper').stop();
	});
	
	// mobile : hide menus when opening nofications
	$("body.css760").on("body.css760 click", "[action='linkNotifications']", function() {
		$('#notifications').slideDown('fast');
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 'fast');
		$('#headerCenter, #menu').hide();
	});
									
	// open classement + suggestion + listbytag + organization sheet + signal abuse + search
	$("body").on("click", "[action='modalOpen']", function() {
		$('#modalBox').fadeIn('fast');
		$('body').addClass('noscroll');
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 0);
		$(".modalRightCol").addClass('activeMobileModal'); // for mobile purpose 
		$('#searchInput').focus();// force text cursor to appear in search input on modal opening
	});
			
	// close modal 
	$("body").on("click", "[action='modalClose']", function() {
		$('body').removeClass('noscroll');
		$('#modalBox').hide();	
		$(".modalLeftCol, .modalRightCol").removeClass('activeMobileModal'); /* for mobile purpose */ 
	});
	
	// notifications 
	$("body").on("click", "[action='linkNotifications']", function() {
		$('#notifications').slideToggle();
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 'fast');
	});
	$("body").on("click", "[action='notifClose']", function() {
		$('#notifications').slideUp('fast');
	});
	
	// hide / show menu preferences
	$("body.css, body.css960").on("click", "[action='openMenuPreferences']", function() {
		$('#menuPreferences').show();
		$(this).hide();
		$('#hideMenuPreferences').show();
	});
	
	$("body.css, body.css960").on("click", "[action='hideMenuPreferences']", function() {
		$('#menuPreferences').hide();
		$(this).hide();
		$('#openMenuPreferences').show();
	});
	
	// go up
	$("body").on("click", "[action='goUp']", function() {
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, '1000');
	});
	
	// close success / error / alert 
	$("body").on("click", "[action='closeBoxSuccess']", function() {
		$(this).parent('.boxSuccess').fadeOut('fast');
	});
	$("body").on("click", "[action='closeBoxError']", function() {
		$(this).parent('.boxError').fadeOut('fast');
	});
	$("body").on("click", "[action='closeBoxAlert']", function() {
		$(this).parent('.boxAlert').fadeOut('fast');
	});
});
