// beta

// scroll video en home
$("body").on("click", "[action='scrollVideo']", function() {    
    $('html, body').animate({ scrollTop: $('#videoFooter').offset().top +1}, '1000');
});

// toggle menu
$("body").on("mousedown touchstart", function(e) {
    var container = $("body.css700 #headerMenu, #publicMenu, [action='toggleMenu']");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('#publicMenu, body.css700 #headerMenu').hide();       
    }
});

$("body").on("click", "[action='toggleMenu']", function() {
    $('#publicMenu, body.css700 #headerMenu').toggle();
});
