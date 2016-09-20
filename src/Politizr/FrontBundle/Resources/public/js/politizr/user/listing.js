// beta
paginatedFunctions[JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS] = debateFollowersListing;
paginatedFunctions[JS_KEY_LISTING_USERS_USER_FOLLOWERS] = userFollowersListing;
paginatedFunctions[JS_KEY_LISTING_USERS_USER_SUBSCRIBERS] = userSubscribersListing;
paginatedFunctions[JS_KEY_LISTING_USERS_BY_FILTERS] = usersByFiltersListing;
paginatedFunctions[JS_KEY_LISTING_USERS_BY_ORGANIZATION] = usersByOrganizationListing;


/**
 * Loading of paginated filters/search listing.
 * @param targetElement
 * @param localLoader
 */
function usersByFiltersListing(init, offset) {
    // console.log('*** usersByFiltersListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    // /!\ propel pagination > num page is used instead of offset
    offset = (typeof offset === "undefined") ? 1 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();

    // console.log(targetElement);
    // console.log(localLoader);

    datas = getCurrentUserFilters();
    datas.push({name: 'offset', value: offset});

    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_FILTERS,
        'user',
        'usersByFilters',
        RETURN_HTML
    );

    return xhrCall(
        document,
        datas,
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

/**
 * Get search filters
 */
function getCurrentUserFilters() {
    // console.log('*** getCurrentUserFilters');

    var filters = [];

    // map
    if ($('.mapBreadcrumbs').find('.current').length) {
        // console.log('sub map uuid');
        uuid = $('.mapBreadcrumbs').find('.current').attr('uuid');
        // console.log(uuid);
        type = $('.mapBreadcrumbs').find('.current').attr('type');
        // console.log(type);
    } else {
        // console.log('map uuid');
        uuid = $('.mapMenu').find('.active').attr('uuid');
        // console.log(uuid);
        type = $('.mapMenu').find('.active').attr('type');
        // console.log(type);
    }

    filters.push({name: 'geoUuid', value: uuid});
    filters.push({name: 'type', value: type});
    
    // profile
    filters.push({name: 'filterProfile', value: $('#profileFilter input:checked').val()});

    // activity
    filters.push({name: 'filterActivity', value: $('#activityFilter input:checked').val()});

    // date
    filters.push({name: 'filterDate', value: $('#dateFilter input:checked').val()});

    // console.log(filters);
    return filters;
}


/**
 * Loading listing user followers content
 * @param targetElement
 * @param localLoader
 * @param uuid
 */
function listingContentUserFollowers(targetElement, localLoader, uuid) {
    // console.log('*** listingContentUserFollowers');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_USER_FOLLOWERS_CONTENT,
        'user',
        'listingFollowersContent',
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
            fullImgLiquid();
        }
        localLoader.hide();
    });
}

/**
 * Loading listing user subscribers content
 * @param targetElement
 * @param localLoader
 * @param uuid
 */
function listingContentUserSubscribers(targetElement, localLoader, uuid) {
    // console.log('*** listingContentUserSubscribers');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_USER_SUBSCRIBERS_CONTENT,
        'user',
        'listingSubscribersContent',
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
            fullImgLiquid();
        }
        localLoader.hide();
    });
}

/**
 * Loading of 18 last user's subscribers listing.
 * @param targetElement
 * @param localLoader
 */
function lastUserSubscribersListing(targetElement, localLoader, uuid) {
    // console.log('*** lastUserSubscribersListing');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_LAST_USER_SUBSCRIBERS,
        'user',
        'lastUserSubscribers',
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
            fullImgLiquid();
        }
        localLoader.hide();
    });
}


/**
 * Loading of 18 last user's followers listing.
 * @param targetElement
 * @param localLoader
 */
function lastUserFollowersListing(targetElement, localLoader, uuid) {
    // console.log('*** lastUserFollowersListing');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_LAST_USER_FOLLOWERS,
        'user',
        'lastUserFollowers',
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
            fullImgLiquid();
        }
        localLoader.hide();
    });
}


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
            fullImgLiquid();
        }
        localLoader.hide();
    });
}

/**
 * Loading of paginated "user subscribers" listing.
 * @param targetElement
 * @param localLoader
 */
function userSubscribersListing(init, offset) {
    // console.log('*** userSubscribersListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#userListing .listSubscribers');
    localLoader = $('#userListing').find('.ajaxLoader').first();

    uuid = $('#userListing').attr('uuid');

    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_USER_SUBSCRIBERS,
        'user',
        'userSubscribers',
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

/**
 * Loading of paginated "user followers" listing.
 * @param targetElement
 * @param localLoader
 */
function userFollowersListing(init, offset) {
    // console.log('*** userFollowersListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#userListing .listFollowers');
    localLoader = $('#userListing').find('.ajaxLoader').first();

    uuid = $('#userListing').attr('uuid');

    var xhrPath = getXhrPath(
        ROUTE_USER_LISTING_USER_FOLLOWERS,
        'user',
        'userFollowers',
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

/**
 * Load filtering tab of "user" listing.
 * @param uuid
 * @param targetElement
 */
function userTabsByOrganization(uuid, targetElement) {
    // console.log('*** userTabsByOrganization');
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_ORGANIZATION_USER_TABS,
        'user',
        'userTabsByOrganization',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'uuid': uuid},
        xhrPath
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
    });
}

/**
 * Loading of paginated "organization" listing.
 * @param targetElement
 * @param localLoader
 */
function usersByOrganizationListing(init, offset) {
    // console.log('*** usersByOrganizationListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#userListing .listTop');
    localLoader = $('#userListing').find('.ajaxLoader').first();
    uuid = $('.filterTabs').attr('uuid');
    orderBy = $('.filterTabs .currentPage').attr('orderBy');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(orderBy);

    var xhrPath = getXhrPath(
        ROUTE_ORGANIZATION_USER_LISTING,
        'user',
        'usersByOrganization',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'uuid': uuid, 'orderBy': orderBy, 'offset': offset},
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

