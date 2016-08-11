// beta

// on document ready
$(function() {
    scoreCounter();
    badgesCounter();
    notificationsLoading();
});




// ******************************************************************* //
//                            DOCUMENTS                                //
// ******************************************************************* //

// bookmark
$("body").on("click", "[action='bookmark']", function(e) {
    // console.log('*** click bookmark');
    
    var targetElement = $(this).closest('.bookmarkBox');
    var localLoader = $(this).closest('.bookmarkBox').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var type = $(this).attr('type');

    return bookmark(targetElement, localLoader, uuid, type);
});



// ******************************************************************* //
//                            NOTIFICATIONS                            //
// ******************************************************************* //

$("body").on("mousedown touchstart", function(e) {
    var container = $("#notifBox, [action='toggleNotifBox']");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $('#notifBox').hide();      
    }
});

$("body").on("click", "[action='toggleNotifBox']", function() {
    $('#notifBox').toggle();
});


// check notification
$("body").on("click", "i[action='notificationCheck']", function(e) {
    // console.log('*** click i notificationCheck');

    e.preventDefault();

    var localLoader = $(this).closest('.notifItem').find('.ajaxLoader').first();
    var context = $(this).closest('.notifItem');
    var uuid = $(this).attr('uuid');
    
    return checkNotificationItem(localLoader, context, uuid);
});

$("body").on("click", "a[action='notificationCheck']", function(e) {
    // console.log('*** click a notificationCheck');

    e.preventDefault();

    var uuid = $(this).closest('span').attr('uuid');
    var targetUrl = $(this).attr('href');

    return checkNotificationLink(uuid, targetUrl);
});

$("body").on("click", "div[action='notificationCheckAll']", function(e) {
    // console.log('*** click notificationCheckAll');

    var localLoader = $(this).closest('#notifBox').find('.ajaxLoader').first();
    
    return chekNotificationAll(localLoader);
});


// ******************************************************************* //
//                            FOLLOWING                                //
// ******************************************************************* //

// follow / unfollow debate
$("body").on("click", "[action='followDebate']", function(e) {
    // // console.log('*** click followDebate');
    
    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_DEBATE,
        'document',
        'follow',
        RETURN_HTML
    );

    var targetElement = $(this).closest('.actionFollow');
    var localLoader = $(this).closest('.actionFollow').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = $(this).attr('way');

    $.when(
        follow(xhrPath, targetElement, localLoader, uuid, way)
    ).done(function(data) {
        if (!data['error']) {
            // update reputation counter
            scoreCounter();
            badgesCounter();

            // refresh timeline
            refreshTimeline();
            stickySidebar();
        }
    });    
});

// follow / unfollow user
$("body").on("click", "[action='followUser']", function(e) {
    // // console.log('*** click followUser');
    
    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_USER,
        'user',
        'follow',
        RETURN_HTML
    );

    var targetElement = $(this).closest('.actionFollow');
    var localLoader = $(this).closest('.actionFollow').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = $(this).attr('way');

    $.when(
        follow(xhrPath, targetElement, localLoader, uuid, way)
    ).done(function(data) {
        if (!data['error']) {
            // update reputation counter
            scoreCounter();
            badgesCounter();

            // refresh timeline
            refreshTimeline();
            stickySidebar();
        }
    });    
});

// follow / unfollow tag
$("body").on("click", "[action='followTag']", function(e) {
    // // console.log('*** click followTag');

    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_TAG,
        'tag',
        'follow',
        RETURN_HTML
    );
    
    var targetElement = $(this).closest('.actionFollow');
    var localLoader = $(this).closest('.actionFollow').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = $(this).attr('way');

    $.when(
        follow(xhrPath, targetElement, localLoader, uuid, way)
    ).done(function(data) {
        if (!data['error']) {
            // refresh tag sidebar
            userTagListing(
                $('.sidebarFollowedTags').find('.tagList').first(),
                $('.sidebarFollowedTags').find('.ajaxLoader').first()
            );
        }
    });    
});

// ******************************************************************* //
//                            NOTATION                                 //
// ******************************************************************* //

// notation
$("body").on("click", "[action='note']", function(e) {
    // console.log('*** click note');
    
    var localLoader = $(this).closest('.notation').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var type = $(this).attr('type');
    var way = $(this).attr('way');

    return noteDocument($(this), localLoader, uuid, type, way);
});

// ******************************************************************* //
//                              MODAL                                  //
// ******************************************************************* //

/**
 * Modal help us
 */
function modalHelpUs() {
    // console.log('*** modalHelpUs');

    $('body').addClass('noScroll');

    var xhrPath = getXhrPath(
        ROUTE_MODAL_HELP_US,
        'modal',
        'helpUs',
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

// ******************************************************************* //
//                   LOCALIZATION ALERT BOX                            //
// ******************************************************************* //

// User localization vars
var service = 'localization';
var xhrRouteCity = ROUTE_CITY_LISTING;

// user profile perso update
$("body").on("click", "button[action='updateLocalization']", function(e) {
    // console.log('click updateLocalization');

    var form = $(this).closest('form');
    var localLoader = $(this).closest('.formBlock').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_USER_PERSO_UPDATE,
        'user',
        'userPersoUpdate',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#alertLocalization').html('');
            $('#alertLocalization').hide();
        }
        localLoader.hide();
    });
});


