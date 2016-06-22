$(function(){
    // video en home page
    $('#video').YTPlayer({
        videoId: '7WHZpebc9q4',
        fitToBackground: true,
        repeat: true,
        mute: true,
        pauseOnScroll: false,
        playerVars: {
            modestbranding: 1,
            autoplay: 1,
            controls: 0,
            showinfo: 0,
            wmode: 'transparent',
            rel: 0,
            disablekb: 1,
            autohide: 0
          }
    });
    
    // img liquid
    $(".publicCard a.publicCardImg").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
    $("#ifNoVideo").imgLiquid({
        fill: true,
        horizontalAlign: "right",
        verticalAlign: "center"
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
    
    // transparent header
    $.fn.isVisible = function() {    
        var rect = this[0].getBoundingClientRect();
        return (
            (rect.height > 0 || rect.width > 0) &&
            rect.bottom >= 0 &&
            rect.right >= 0 &&
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.left <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };
    function triggerTransparentHeader() {
        var elementToDetect = $('#publicVideo');  
        if (elementToDetect.isVisible()) {
            $("#header").addClass("transparentHeader");
            $("#triggerVideo").css("height", "calc(100% - 60px)");
            $("body.css700 #triggerVideo").css("height", "calc(100% - 50px)");
        } else {
            $("#header").removeClass("transparentHeader");
            $("#triggerVideo").css("height", "calc(100% + 60px)");
            $("body.css700 #triggerVideo").css("height", "calc(100% + 50px)");
        }  
    }
    
    $(document)
        .ready(function(e){
            triggerTransparentHeader();
        });
    
    $(window)
        .scroll(function(e){
            triggerTransparentHeader();   
            $('#publicMenu, body.css700 #headerMenu').hide(); //hide menu au scroll
        });
});