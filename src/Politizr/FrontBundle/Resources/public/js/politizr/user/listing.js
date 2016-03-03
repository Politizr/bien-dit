// beta
paginatedFunctions[JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS] = debateFollowersListing;

/**
 * Loading of 18 last debate's followers listing.
 * @param targetElement
 * @param localLoader
 */
function lastDebateFollowersListing(targetElement, localLoader, uuid) {
    // console.log('*** lastDebateFollowersListing');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
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


/**
 * Loading of paginated "debate followers" listing.
 * @param targetElement
 * @param localLoader
 */
function debateFollowersListing(init, offset) {
    // console.log('*** debateFollowersListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#userListing .listFollowers');
    localLoader = $('#userListing').find('.ajaxLoader').first();

    uuid = $('#userListing').attr('uuid');

    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_DEBATE_FOLLOWERS,
        'user',
        'debateFollowers',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'uuid': uuid, 'offset': offset},
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#listingScrollNav').remove();
            if (init) {
                targetElement.html(data['html']);
            } else {
                targetElement.append(data['html']);
            }
            initPaginateNextWaypoint();
            fullImgLiquid();
        }
        localLoader.hide();
    });
}
