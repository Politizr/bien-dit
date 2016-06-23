// beta

// scroll video en home
$("body").on("click", "[action='scrollVideo']", function() {    
    $('html, body').animate({ scrollTop: $('#videoFooter').offset().top +1}, '1000');
});
