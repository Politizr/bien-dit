$(document)
    .ready(function(e){
        triggerTransparentHeader();
    });

$(window)
    .scroll(function(e){
        triggerTransparentHeader();   
        $('#publicMenu, body.css700 #headerMenu').hide(); //hide menu au scroll
    });

// open video modal sur page concept
$("body").on("click", "[action='openVideoModal']", function() {
    $('body').addClass('noScroll');
    $('#modalVideo').fadeIn();
});

// close modal 
$("body").on("click", "[action='closeVideoModal']", function() {
    $('body').removeClass('noScroll');
    $('#modalVideo').hide();
});     

