// beta

// on document ready
$(function() {
    // console.log('*** global document ready');
    Waypoint.destroyAll();
    fullImgLiquid();
    $('#ajaxGlobalLoader').hide();
    $('.bubblesHelper').hide();

    $('.headerGrpMenu').hide();
});

// mobile
// $("body").on("click", "[action='showMobileSidebar']", function() {
//     $('body.css700 .sticky-wrapper').removeClass("hideSidebarForMobile");
//     $('body.css700 #actionShowMobileSidebar').hide();
//     $('body.css700 #actionHideMobileSidebar').show();
// });
// $("body").on("click", "[action='hideMobileSidebar']", function() {
//     $('body.css700 .sticky-wrapper').addClass("hideSidebarForMobile");
//     $('body.css700 #actionHideMobileSidebar').hide();
//     $('body.css700 #actionShowMobileSidebar').show();
// });

// Fermeture d'une box
$('body').on("click", "[action='closeBox']", function(e){
    // cache parent div
    $(this).closest('div').hide();
});

// Close id check ok
$('body').on("click", "[action='closeIdCheckOk']", function(e){
    $('#alertIdCheckOk').hide();
});

// close modal
$("body").on("click", "[action='closeModal']", function() {
    // console.log('*** closeModal');
    $('body').removeClass('noScroll');
    $('#modal').hide();
});

// Scroll haut de page
$("body").on("click", "[action='goUp']", function() {
    stickySidebar(true);
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

// ******************************************************************* //
//                              MAIN MENU                              //
// ******************************************************************* //

// toggle grp menu
$("body").on("mousedown touchstart", function(e) {
//$(document).mousedown(function (e) {
    var container = $(".headerGrpMenu, [action='toggleGrpMenuCms']");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('.headerGrpMenu').hide();
    }
});
$("body").on("click", "[action='toggleGrpMenuCms']", function() {
    $(this).find('.headerGrpMenu').toggle();
    $('body.css700 #headerMenu').hide();    
});

// toggle grp menu
$("body").on("mousedown touchstart", function(e) {
//$(document).mousedown(function (e) {
    var container = $(".headerGrpMenu, [action='toggleGrpMenuGroup']");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('.headerGrpMenu').hide();     
    }
});
$("body").on("click", "[action='toggleGrpMenuGroup']", function() {
    $(this).find('.headerGrpMenu').toggle();
    $('body.css700 #headerMenu').hide();    
});


// ******************************************************************* //
//                     SOCIAL NETWORK SHARING                          //
// ******************************************************************* //
$("body").on("click", "[action='shareFB']", function(e) {
    window.open('https://facebook.com/sharer.php?u='+$(this).attr('url'), 'facebook_share', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

$("body").on("click", "[action='shareTW']", function(e) {
    window.open('https://twitter.com/share?url='+$(this).attr('url')+'&text='+$(this).attr('tweetText')+'&via=Politizr&lang=fr&count=none', 'tweet_it', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
});

$("body").on("click", "[action='shareGG']", function(e) {
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

// ******************************************************************* //
//                             REFRESH                                 //
// ******************************************************************* //

/**
 * Refresh timeline
 */
function refreshTimeline() {
    if ($('.myfeed').length) {
        Waypoint.destroyAll();
        timelineList();
    }
}

/**
 * Refresh debate followers infos
 */
function refreshDebateFollowers() {
    if ($('#subjectFollowers').length) {
        lastDebateFollowersListing(
            $('.sidebarSubjectFollowers').find('#subjectFollowers').first(),
            $('.sidebarSubjectFollowers').find('.ajaxLoader').first(),
            $('#subjectFollowers').attr('uuid')
        );
    }
}

/**
 * Refresh user followers infos
 */
function refreshUserFollowers() {
    if ($('#userFollowers').length) {
        lastUserFollowersListing(
            $('.sidebarUserFollowers').find('#userFollowers').first(),
            $('.sidebarUserFollowers').find('.ajaxLoader').first(),
            $('#userFollowers').attr('uuid')
        );
    }
}

// ******************************************************************* //
//                              STICKY                                 //
// ******************************************************************* //

// global sticky instance
var stickyWaypoint;

/**
 * Manage creation or not of a sticky sidebar
 */
function stickySidebar(forceReload)
{
    // console.log('*** stickySidebar');
    // console.log(forceReload);
    
    // var context = Waypoint.Context.findByElement(window);
    // if (typeof context === 'undefined') {
    //     // console.log('Waypoint sticky does not exist');
    //     stickyWaypoint = new Waypoint.Sticky({
    //         element: $('#sidebar'),
    //         offset: 'bottom-in-view'
    //     });
    //     $(".sticky-wrapper").addClass("hideSidebarForMobile");
    // } else if (context instanceof Waypoint.Context) {
    //     // console.log(context);
    //     // console.log('Waypoint sticky already exist');
    // }

    // console.log(stickyWaypoint);
    if (typeof stickyWaypoint === 'undefined') {
        // console.log('Waypoint sticky does not exist');
        stickyWaypoint = createStickySidebar();
    } else {
        // console.log('Waypoint sticky already exist');
        if (forceReload) {
            // console.log('force sticky reloading');

            stickyWaypoint.destroy();
            stickyWaypoint = createStickySidebar();
        }
    }
}

/**
 * Create sticky sidebar
 */
function createStickySidebar()
{
    // console.log('*** createStickySidebar');
    sticky = new Waypoint.Sticky({
        element: $('#sidebar'),
        offset: 'bottom-in-view'
    });
    // $(".sticky-wrapper").addClass("hideSidebarForMobile");

    return sticky;
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
    // full size image in grp
    $("#grpBriefHeaderImg, .grpCharterImg, .grpImg").imgLiquid({
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
    // console.log("*** updateUrl");
    // console.log(url);

    if (typeof (history.pushState) != "undefined") {
        history.pushState({}, '', url);
    } else {
        // console.log("Your browser does not support HTML5.");
    }
}


// ******************************************************************* //
//                              MODAL                                  //
// ******************************************************************* //

$('body').on("click", "[action='openCgu']", function(e){
    // console.log('*** click openCgu');

    return modalCgu();
});

$('body').on("click", "[action='openCgv']", function(e){
    // console.log('*** click openCgv');

    return modalCgv();
});

$('body').on("click", "[action='openCharte']", function(e){
    // console.log('*** click openCharte');

    var uuid = $(this).attr('uuid');

    return modalCharte(uuid);
});

$('body').on("click", "[action='openGlobalHelper']", function(e){
    // console.log('*** click openGlobalHelper');

    return modalGlobalHelper();
});



/**
 * Modal CGU
 */
function modalCgu() {
    // console.log('*** modalCgu');

    var xhrPath = getXhrPath(
        ROUTE_MODAL_CGU,
        'modal',
        'cgu',
        RETURN_HTML
    );

    return xhrCall(
        document,
        null,
        xhrPath
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#modalContainer').html(data['html']);
        }
    });
};

/**
 * Modal CGV
 */
function modalCgv() {
    // console.log('*** modalCgv');

    var xhrPath = getXhrPath(
        ROUTE_MODAL_CGV,
        'modal',
        'cgv',
        RETURN_HTML
    );

    return xhrCall(
        document,
        null,
        xhrPath
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#modalContainer').html(data['html']);
        }
    });
};

/**
 * Modal Charte
 */
function modalCharte(uuid) {
    // console.log('*** modalCharte');
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_MODAL_CHARTE,
        'modal',
        'charte',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#modalContainer').html(data['html']);
        }
    });
};
