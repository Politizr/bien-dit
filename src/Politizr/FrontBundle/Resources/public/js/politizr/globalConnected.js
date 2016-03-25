// beta

// on document ready
$(function() {
    scoreCounter();
    badgesCounter();
    notificationsLoading();
})

// ************************** NOTIFICATIONS ************************** //

$(document).mousedown(function (e) {
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
    console.log('*** click i notificationCheck');

    e.preventDefault();

    var localLoader = $(this).closest('.notifItem').find('.ajaxLoader').first();
    var context = $(this).closest('.notifItem');
    var uuid = $(this).attr('uuid');
    
    return checkNotificationItem(localLoader, context, uuid);
});

$("body").on("click", "a[action='notificationCheck']", function(e) {
    console.log('*** click a notificationCheck');

    e.preventDefault();

    var uuid = $(this).closest('span').attr('uuid');
    var targetUrl = $(this).attr('href');

    return checkNotificationLink(uuid, targetUrl);
});

$("body").on("click", "div[action='notificationCheckAll']", function(e) {
    console.log('*** click notificationCheckAll');

    var localLoader = $(this).closest('#notifBox').find('.ajaxLoader').first();
    
    return chekNotificationAll(localLoader);
});
