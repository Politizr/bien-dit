/**
 * Loading of 18 last debate's followers listing.
 * @param targetElement
 * @param localLoader
 */
function lastDebateFollowersListing(targetElement, localLoader, uuid) {
    console.log('*** lastDebateFollowersListing');
    console.log(targetElement);
    console.log(localLoader);
    console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_LAST_DEBATE_FOLLOWERS,
        'user',
        'lastDebateFollowers',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid },
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

