// beta

/**
 * Bookmark
 */
function bookmark(targetElement, localLoader, uuid, type) {
    // console.log('*** bookmark');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_BOOKMARK,
        'document',
        'bookmark',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}
