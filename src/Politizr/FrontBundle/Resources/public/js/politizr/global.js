// beta
// on document ready
$(function() {
    // console.log('*** global document ready');
    fullImgLiquid();
    Waypoint.destroyAll();
    $('#ajaxGlobalLoader').hide();
});

// mobile
$(".sticky-wrapper").addClass("hideSidebarForMobile");
$("body").on("click", "[action='showMobileSidebar']", function() {
    $('body.css700 .sticky-wrapper').removeClass("hideSidebarForMobile");
    $('body.css700 #actionShowMobileSidebar').hide();
    $('body.css700 #actionHideMobileSidebar').show();
});
$("body").on("click", "[action='hideMobileSidebar']", function() {
    $('body.css700 .sticky-wrapper').addClass("hideSidebarForMobile");
    $('body.css700 #actionHideMobileSidebar').hide();
    $('body.css700 #actionShowMobileSidebar').show();
});

// Fermeture d'une box
$('body').on('click', "[action='closeBox']", function(e){
    // cache parent div
    $(this).closest('div').hide();
});

// main menu
$(document).mousedown(function (e) {
    var container = $("#menu, [action='toggleMenu']");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('#menu, body.css700 #headerMenu').hide();      
    }
});

$("body").on("click", "[action='toggleMenu']", function() {
    $('#menu, body.css700 #headerMenu').toggle();
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

// refresh timeline
function refreshTimeline() {
    if ($('.myfeed').length) {
        Waypoint.destroyAll();
        timelineList();
    }
}

// sticky sidebar
function stickySidebar() {
    var sticky = new Waypoint.Sticky({
        element: $('#sidebar'),
        offset: 'bottom-in-view'
    })    
}

// imgLiquid
function fullImgLiquid() {
    // console.log('*** fullImgLiquid');

    // full size image in avatars
    $(".avatar30, .avatar40").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
    // full size image in suggestions + minipost + new post
    $(".suggItemImg, .miniPostImg, .postImg, #cardPostImg").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
    // full size image in edition
    $(".postIllustration").imgLiquid({
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
 * Init charts with data
 * @param string ID element name
 * @param array data
 * @return chart instance
 */
function loadDataCharts(elementId, lineChartData) {
    // console.log('*** loadReputationDataCharts');
    // console.log(lineChartData);

    if (lineChartData === "undefined") {
        return false;
    }

    var ctx = document.getElementById(elementId).getContext("2d");
    var myChart = new Chart(ctx).Line(lineChartData, {
        responsive: true,
        animation: false,
        bezierCurve : false,
        scaleFontFamily: "'nerissemibold'",
        scaleFontSize: 8,
        scaleFontColor: "#a9a9a9",
        tooltipTemplate: "<%= value %> POINTS",
        tooltipFontFamily: "'nerisblack'",
        tooltipFontSize: 10,
        tooltipFillColor: "#2d2d2d",
        tooltipFontColor: "#fff",
        tooltipCornerRadius : 3,
        tooltipYPadding: 10,
        tooltipXPadding: 10,
        tooltipCaretSize: 4,
        pointDotRadius : 4,
        pointDotStrokeWidth : 2,
        scaleShowGridLines : false,
        scaleGridLineColor : "#dbdcdd",
        scaleLineColor: "#dbdcdd",
        datasetStrokeWidth : 2,
        pointHitDetectionRadius : 5,
        scaleBeginAtZero: false
    });

    return myChart;
}

/**
 * JS update of URL
 *
 * @param url
 */
function updateUrl(url) {
    console.log("*** updateUrl");
    console.log(url);

    if (typeof (history.pushState) != "undefined") {
        history.pushState({}, '', url);
    } else {
        console.log("Browser does not support HTML5.");
    }
}