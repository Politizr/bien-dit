// beta

/**
 * Follow / Unfollow debate / user / tag
 */
function follow(xhrPath, targetElement, localLoader, uuid, way) {
    // console.log('*** follow');
    // console.log(xhrPath);
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(way);

    return xhrCall(
        document,
        { 'uuid': uuid, 'way': way },
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            // update follow / unfollow
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}
