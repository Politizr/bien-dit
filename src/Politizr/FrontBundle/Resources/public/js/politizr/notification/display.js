// beta

/**
 * Notification loading
 */
function notificationsLoading(){
    // console.log('*** notificationsLoading');

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_LOADING,
        'notification',
        'notificationsLoad',
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
            // MAJ compteur
            note = parseInt(data['counterNotifs']);
            if (note > 0) {
                $('#notifCounterNew').html(data['counterNotifs']).show();
            } else {
                $('#notifCounterNew').html('-').hide();
            }

            // MAJ listing des notifs
            $('#notifBox').html(data['html']);
        }
    });
}

/**
 * Check a notification item
 * @param targetElement
 * @param localLoader
 * @param uuid
 */
function checkNotificationItem(localLoader, context, uuid) {
    // console.log('*** checkNotificationItem');
    // console.log(localLoader);
    // console.log(context);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CHECK,
        'notification',
        'notificationCheck',
        RETURN_BOOLEAN
    );

    return xhrCall(
        context,
        { 'uuid': uuid },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $(this).find('.notifHighlight').removeClass();
            $(this).find('.icon-check-incircle').remove();
            $(this).addClass('viewedNotif');

            note = parseInt($('#notifCounterNew').text()) - 1;
            if (note > 0) {
                $('#notifCounterNew').html(note);
            } else {
                $('#notifCounterNew').html('-').hide();
            }
        }
        localLoader.hide();
    });
}

/**
 * Check a notification link
 * @param uuid
 * @param targetUrl
 */
function checkNotificationLink(uuid, targetUrl) {
    // console.log('*** checkNotificationLink');
    // console.log(uuid);
    // console.log(targetUrl);
    
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CHECK,
        'notification',
        'notificationCheck',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
            $('#ajaxGlobalLoader').hide();
        } else {
            window.location = targetUrl;
        }
    });
}

/**
 * Check all notifications
 * @param uuid
 * @param targetUrl
 */
function chekNotificationAll(localLoader) {
    // console.log('*** chekNotificationAll');
    // console.log(localLoader);
    
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CHECK_ALL,
        'notification',
        'notificationsCheckAll',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        null,
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
            $('#ajaxGlobalLoader').hide();
        } else {
            localLoader.hide();

            // MAJ du style
            $('.notifItem').addClass('viewedNotif');
            $('.notifItem').find('.notifHighlight').removeClass();
            $('.notifItem').find('.icon-check-incircle').remove();

            // MAJ du compteur
            $('#notifCounterNew').html('-').hide();
        }
    });
}

