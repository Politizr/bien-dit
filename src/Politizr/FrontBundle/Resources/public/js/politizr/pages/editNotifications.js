// beta
$(function() {
    stickySidebar();
});

// Subscribe email notif
$("body").on("click", "[action='notifEmailSubscribe']", function(e) {
    // console.log('*** click notifEmailSubscribe');
    
    var localLoader = $(this).closest('.notificationsPrefsItem').find('.ajaxLoader').first();

    var uuid = $(this).attr('uuid');
    // console.log(uuid);

    return notifEmailSubscribe(uuid, $(this), localLoader);
});

// Unsubscribe email notif
$("body").on("click", "[action='notifEmailUnsubscribe']", function(e) {
    // console.log('*** click notifEmailUnsubscribe');
    
    var localLoader = $(this).closest('.notificationsPrefsItem').find('.ajaxLoader').first();

    var uuid = $(this).attr('uuid');
    // console.log(uuid);

    return notifEmailUnsubscribe(uuid, $(this), localLoader);
});
