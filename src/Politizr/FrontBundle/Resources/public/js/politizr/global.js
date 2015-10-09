// on document ready
$(function() {
    fullImgLiquid();
    $('#ajaxGlobalLoader').hide();
});

// Fermeture d'une box
$('body').on('click', "[action='closeBox']", function(e){
    // cache parent div
    $(this).closest('div').hide();
});

// Fermeture d'une modal
$("body").on("click", "[action='modalClose']", function() {
    $('body').removeClass('noscroll');
    $('#modalBoxContent').html('');
    $(this).closest('.modal').hide();
    $(".modalLeftCol, .modalRightCol").removeClass('activeMobileModal'); /* for mobile purpose */ 
});

// Cancel in modal
$("body").on("click", "[action='cancelModal']", function() {
    $("[action='modalClose']").trigger("click");
});

// Scroll haut de page
$("body").on("click", "[action='goUp']", function() {
    $('html, body').animate({
        scrollTop: $("body").offset().top
    }, '1000');
});

// Reload page
$("body").on("click", "[action='reloadPage']", function() {
    location.reload()
});

// Copyright sur l'image des contenus
$("body").on("click", "[action='showCopyright']", function() {
    $('#copyrightBox').toggle();       
});

// hide / show menu preferences
$("body").on("click", "[action='openMenuPreferences']", function() {
    $('#menuPreferences').show();
    $(this).hide();
    $('#hideMenuPreferences').show();
});

$("body").on("click", "[action='hideMenuPreferences']", function() {
    $('#menuPreferences').hide();
    $(this).hide();
    $('#openMenuPreferences').show();
});

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

// hide / show helper
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

// social network sharing
$("body").on("click", "[action='share-fb']", function(e) {
    window.open('http://www.facebook.com/sharer.php?u='+$(this).attr('url'), 'facebook_share', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

$("body").on("click", "[action='share-tw']", function(e) {
    window.open('http://twitter.com/share?url='+$(this).attr('url')+'&text='+$(this).attr('tweetText')+'&hashtags=Politizr&lang=fr&count=none', 'tweet_it', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

$("body").on("click", "[action='share-gg']", function(e) {
    window.open('https://plus.google.com/share?url='+$(this).attr('url'), 'google_plus_share', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

// imgLiquid
function fullImgLiquid() {
    // console.log('*** fullImgLiquid');

    // full size image on public homepage
    $("#homeClaim, #homeOfficial").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });

    // document read / edition
    $("#illustrationMain, #illustration_l, #illustration_r, #currentPhoto").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });

    // user read / edition
    $("#profileHeaderIllustration").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });

    // debate summary 
    $(".postSummary").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });

    // avatars
    $(".avatar15, .avatar40, .avatar60").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });

    // timeline item illustration
    $(".timelineItemIllustration").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });

    // reaction to post illustration
    $(".reactionsToPostItemIllustration").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
}

// sticky date
function stickyDate() {
    // console.log('*** stickyDate');
    
    // sticky day for css
    // with timelineHeader
    if( $('body.css #timelineHeader').is(':visible') ) {
        $('body.css .timelineDayContainer').stickyDayWithTimelineHeader({stickyClass : 'timelineDay'});
        $('body.css #timeline').stickyTimelineHeader({stickyClass : 'stickyTimelineHeader'});
        }
        // without timelineHeader
    else {
        $('body.css .timelineDayContainer').stickyDayWithoutTimelineHeader({stickyClass : 'timelineDay'});
    }
    // sticky timeline dates for css 1000
    $('body.css1000 .timelineDayContainer').stickyDay1000({stickyClass : 'timelineDay'});
    // sticky timeline dates for css 760
    $('body.css760 .timelineDayContainer').stickyDay760({stickyClass : 'timelineDay'});
}

/**
 * get last week date
 * @return Date
 */
function getLastWeek(){
    var today = new Date();
    var lastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);

    return lastWeek ;
}    
