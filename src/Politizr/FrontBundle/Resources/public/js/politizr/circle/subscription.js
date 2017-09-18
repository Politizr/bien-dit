
/**
 * Loading of paginated filters/search listing.
 * @param targetElement
 * @param localLoader
 * @param uuid
 * @param way
 */
function subscribeCircle(targetElement, localLoader, uuid, way) {
    console.log('*** subscribeCircle');
    console.log(targetElement);
    console.log(localLoader);
    console.log(uuid);
    console.log(way);

    var xhrPath = getXhrPath(
        ROUTE_CIRCLE_SUBSCRIPTION,
        'circle',
        'subscribeCircle',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'way': way },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);

            if (way == 'subscribe') {
                window.location = data['redirectUrl'];
            }
        }
        localLoader.hide();
    });
}
