// on document ready
$(function() {
    // console.log('*** global document ready');
    fullImgLiquid();
    Waypoint.destroyAll();
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
    $('body.css #menuPreferences, body.css1000 #menuPreferences').show();
    $('body.css #hideMenuPreferences, body.css1000 #hideMenuPreferences').show();
    $('body.css #openMenuPreferences, body.css1000 #openMenuPreferences').hide();
    
    $('body.css760 #menuPreferences').toggle();
	$('body.css760 #headerCenter, body.css760 #menu, body.css760 #fixedActions').hide();
});

$("body").on("click", "[action='hideMenuPreferences']", function() {
    $('body.css #menuPreferences, body.css1000 #menuPreferences').hide();
    $('body.css #hideMenuPreferences, body.css1000 #hideMenuPreferences').hide();
    $('body.css #openMenuPreferences, body.css1000 #openMenuPreferences').show();
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
    $('.helperMark').css("color", "#b8b9bc");i
    $(this).next('.helperSlider').toggle();
    $(this).css("color", "#079db5");
});

$("body").on("click", "[action='hideHelper']", function() {
    $('.helperSlider').hide();
    $('.helperMark').css("color", "#b8b9bc");i
});

// social network sharing
$("body").on("click", "[action='share-fb']", function(e) {
    window.open('https://facebook.com/sharer.php?u='+$(this).attr('url'), 'facebook_share', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

$("body").on("click", "[action='share-tw']", function(e) {
    window.open('https://twitter.com/share?url='+$(this).attr('url')+'&text='+$(this).attr('tweetText')+'&hashtags=Politizr&lang=fr&count=none', 'tweet_it', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

$("body").on("click", "[action='share-gg']", function(e) {
    window.open('https://plus.google.com/share?url='+$(this).attr('url'), 'google_plus_share', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

// blocked notation
$(document).on("touchstart click", function(){
    if( $('.noteBubble').is(':visible')) {
        $('.noteBubble').hide();    
    }
    if( $('.menuOnMap').is(':visible')) {
        $('.menuOnMap').hide();    
    }
});

// toggle menu dom tom
$("body").on("click", "[action='toggleDomTom']", function(e) {
    e.stopPropagation();
    $('.menuOnMap').toggle();
});

// toggle blocked notation
$("body").on("touchstart click", ".blockedVote", function(e) {
    e.stopPropagation();
    $(this).find('.noteBubble').toggle();
});
    
// toggle confidential info text
$("body").on("mouseover", "[action='confidentialToggle']", function() {
    $(this).next('.confidentialInfo').show();
});

$("body").on("mouseout", "[action='confidentialToggle']", function() {
    $(this).next('.confidentialInfo').hide();   
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
    $(".profileHeaderIllustration").imgLiquid({
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

    // dashboard popular debate 
    $(".halfWidthThumb").imgLiquid({
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

/**
 * get last week date
 * @return Date
 */
function getLastWeek(){
    var today = new Date();
    var lastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);

    return lastWeek ;
}    

/**
 * Character counting for comment
 */
function commentTextCounter() {
    // console.log('*** commentTextCounter');

    $('#comment_description').textcounter({
        type                     : "character",            // "character" or "word"
        min                      : 5,                      // minimum number of characters/words
        max                      : 500,                    // maximum number of characters/words, -1 for unlimited, 'auto' to use maxlength attribute
        countContainerElement    : "div",                  // HTML element to wrap the text count in
        countContainerClass      : "commentCountWrapper",   // class applied to the countContainerElement
        textCountClass           : "textCount",           // class applied to the counter length
        inputErrorClass          : "error",                // error class appended to the input element if error occurs
        counterErrorClass        : "error",                // error class appended to the countContainerElement if error occurs
        counterText              : "Caractères: ",        // counter text
        errorTextElement         : "div",                  // error text element
        minimumErrorText         : "Minimum: 5 caractères",      // error message for minimum not met,
        maximumErrorText         : "Maximum: 500 caractères",     // error message for maximum range exceeded,
        displayErrorText         : true,                   // display error text messages for minimum/maximum values
        stopInputAtMaximum       : false,                   // stop further text input if maximum reached
        countSpaces              : true,                  // count spaces as character (only for "character" type)
        countDown                : true,                  // if the counter should deduct from maximum characters/words rather than counting up
        countDownText            : "Caractères restants: ",          // count down text
        countExtendedCharacters  : false,                       // count extended UTF-8 characters as 2 bytes (such as Chinese characters)    
    });
};