$(function(){	
	// show full bio
    $("body").on("click", "[action='ShowCompleteBio']", function() {
	    $('#profileBio').css({overflow: "visible", height:"auto" });
		$('#hideCompleteBio').show();
		$('#showCompleteBio').hide();
	});
	
	// hide full bio
	$("body").on("click", "[action='HideCompleteBio']", function() {
	    $('#profileBio').css({overflow: "hidden", height:"360px" });
		$('#hideCompleteBio').hide();
		$('#showCompleteBio').show();
	});
	
});