// beta

/**
 * Loading listing user badges
 * @param targetElement
 * @param localLoader
 * @param uuid
 */
function userMiniBadgeListing(targetElement, localLoader, uuid) {
    // console.log('*** userMiniBadgeListing');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_BADGES,
        'user',
        'userMiniBadges',
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


/**
 * Show reputation score if authorized
 */
function showReputation()
{
    if (!$('#reputationScore').is(':visible')) {
        return;
    }
    
    getUserScore(uuid).done(function(data) {
        // console.log(data['html']);
        $('.sidebarBadges').find('h5').first().html(data['html'] + ' points de réputation');
    });
}
