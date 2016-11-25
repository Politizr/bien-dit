// beta

/**
 * Email notif subscribe
 *
 * @param uuid
 * @param targetElement
 * @param localLoader
 */
function notifEmailSubscribe(uuid, targetElement, localLoader) {
    // console.log('*** notifEmailSubscribe');
    // console.log(uuid);
    // console.log(targetElement);
    // console.log(localLoader);

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_EMAIL_SUBSCRIBE,
        'notification',
        'notifEmailSubscribe',
        RETURN_BOOLEAN
    );

    return xhrCall(
        targetElement,
        { 'uuid': uuid },
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.attr('checked', 'checked');
            targetElement.attr('action', 'notifEmailUnsubscribe');
        }
        localLoader.hide();
    });
}

/**
 * Email notif unsubscribe
 *
 * @param uuid
 * @param targetElement
 * @param localLoader
 */
function notifEmailUnsubscribe(uuid, targetElement, localLoader) {
    // console.log('*** notifEmailSubscribe');
    // console.log(uuid);
    // console.log(targetElement);
    // console.log(localLoader);

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_EMAIL_UNSUBSCRIBE,
        'notification',
        'notifEmailUnsubscribe',
        RETURN_BOOLEAN
    );

    return xhrCall(
        targetElement,
        { 'uuid': uuid },
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.removeAttr('checked');
            targetElement.attr('action', 'notifEmailSubscribe');
        }
        localLoader.hide();
    });
}
