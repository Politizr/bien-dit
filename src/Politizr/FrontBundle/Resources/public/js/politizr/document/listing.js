// beta
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_TAG] = documentsByTagListing;
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION] = documentsByOrganizationListing;
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND] = documentsByRecommendListing;
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS] = myDraftsByUserListing;
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_USER_BOOKMARKS] = myBookmarksByUserListing;
paginatedFunctions[JS_KEY_LISTING_PUBLICATIONS_BY_USER_PUBLICATIONS] = publicationsByUserListing;
paginatedFunctions[JS_KEY_LISTING_PUBLICATIONS_BY_FILTERS] = publicationsByFiltersListing;


/**
 * Loading of paginated filters/search listing.
 * @param targetElement
 * @param localLoader
 */
function publicationsByFiltersListing(init, offset) {
    // console.log('*** publicationsByFiltersListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();

    // console.log(targetElement);
    // console.log(localLoader);

    datas = getCurrentPublicationFilters();
    datas.push({name: 'offset', value: offset});

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_FILTERS,
        'document',
        'publicationsByFilters',
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
function getCurrentPublicationFilters() {
    // console.log('*** getCurrentPublicationFilters');

    var filters = [];

    // map
    if ($('.mapBreadcrumbs').find('.current').length) {
        // console.log('sub map uuid');
        uuid = $('.mapBreadcrumbs').find('.current').attr('uuid');
        // console.log(uuid);
    } else {
        // console.log('map uuid');
        uuid = $('.mapMenu').find('.active').attr('uuid');
    }

    filters.push({name: 'geoTagUuid', value: uuid});
    
    // publication
    filters.push({name: 'filterPublication', value: $('#publicationFilter input:checked').val()});

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
 * Loading of paginated user publications listing.
 * @param targetElement
 * @param localLoader
 */
function publicationsByUserListing(init, offset) {
    // console.log('*** publicationsByUserListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();
    uuid = $('.pseudoTabs').attr('uuid');
    orderBy = $('.pseudoTabs .currentPage').attr('filter');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(orderBy);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_USER_PUBLICATIONS,
        'document',
        'publicationsByUser',
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


/**
 * User's publication listing
 *
 * @param boolean init
 * @param integer offset
 */
function myDraftsByUserListing(init, offset) {
    // console.log('*** myDraftsByUserListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_MY_DRAFTS,
        'document',
        'myDraftsPaginated',
        RETURN_HTML
        );

    return xhrCall(
        document,
        {'offset': offset},
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
 * User's publication listing
 *
 * @param boolean init
 * @param integer offset
 */
function myBookmarksByUserListing(init, offset) {
    // console.log('*** myBookmarksByUserListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_MY_BOOKMARKS,
        'document',
        'myBookmarksPaginated',
        RETURN_HTML
        );

    return xhrCall(
        document,
        {'offset': offset},
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
 * Document recommend next/prev page
 */
$("body").on("click", "[action='prevNextLink']", function(e, waypoint) {
    // console.log('*** click prevNextLink');
    e.preventDefault();
    documentsByRecommendListingNav(
        $(this).attr('month'),
        $(this).attr('year')
    )
    .then(function() {
        documentsByRecommendListing();
    });
});

/**
 * Compute next/prev in documents recommend listing
 */
function documentsByRecommendListingNav(month, year) {
    // console.log('*** documentsByRecommendListingNav');
    // console.log(month);
    // console.log(year);

    targetElement = $('.listTopHeader');
    localLoader = $('.listTopHeader').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_RECOMMEND_NAV,
        'document',
        'documentsByRecommendNav',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'month': month, 'year': year},
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);

            $('#documentListing').attr('month', data['numMonth']);
            $('#documentListing').attr('year', data['year']);

            updateUrl(data['month']+'-'+data['year']);
        }
        localLoader.hide();
    });

}

/**
 * Loading of paginated "recommended" listing.
 * @param targetElement
 * @param localLoader
 */
function documentsByRecommendListing(init, offset) {
    // console.log('*** documentsByRecommendListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();

    month = $('#documentListing').attr('month');
    year = $('#documentListing').attr('year');

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_RECOMMEND,
        'document',
        'documentsByRecommend',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'month': month, 'year': year, 'offset': offset},
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
 * Loading of paginated "tag" listing.
 * @param targetElement
 * @param localLoader
 */
function documentsByTagListing(init, offset) {
    // console.log('*** documentsByTagListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();
    uuid = $('.pseudoTabs').attr('uuid');
    orderBy = $('.pseudoTabs .currentPage').attr('orderBy');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(orderBy);

    var xhrPath = getXhrPath(
        ROUTE_TAG_LISTING,
        'document',
        'documentsByTag',
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

/**
 * Loading of paginated "organization" listing.
 * @param targetElement
 * @param localLoader
 */
function documentsByOrganizationListing(init, offset) {
    // console.log('*** documentsByTagListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();
    uuid = $('.pseudoTabs').attr('uuid');
    orderBy = $('.pseudoTabs .currentPage').attr('orderBy');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(orderBy);

    var xhrPath = getXhrPath(
        ROUTE_ORGANIZATION_LISTING,
        'document',
        'documentsByOrganization',
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

/**
 * Loading of top "document" listing.
 * @param targetElement
 * @param localLoader
 */
function topDocumentListing(targetElement, localLoader) {
    // console.log('*** topDocumentListing');
    // console.log(targetElement);
    // console.log(localLoader);
    
    // Tag form filter
    var datas = $('#documentFilter').serializeArray();
    // console.log(datas);
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'documentFilterDate[]', value: 'lastMonth'});
    }
    // console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_TOP,
        'document',
        'topDocuments',
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
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}

/**
 * Loading of suggestion "document" listing.
 * @param targetElement
 * @param localLoader
 */
function suggestionDocumentListing(targetElement, localLoader) {
    // console.log('*** suggestionDocumentListing');
    // console.log(targetElement);
    // console.log(localLoader);
    
    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_SUGGESTION,
        'document',
        'suggestionDocuments',
        RETURN_HTML
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
        } else {
            targetElement.html(data['html']);

            // init cycle
            $('.cycle-slideshow').cycle();

            // img liquid reinit
            fullImgLiquid();
        }
        localLoader.hide();
    });
}
