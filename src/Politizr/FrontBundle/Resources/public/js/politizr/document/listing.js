// paginated listing method variables
var paginatedFunctions = {};
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_TAG] = documentsByTagListing;
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION] = documentsByOrganizationListing;

/**
 * Timeline's next page
 */
$("body").on("click", "[action='listingNext']", function(e, waypoint) {
    // console.log('timelinePaginatedNext next');

    var key = $('#moreResults').attr('key');
    // console.log(key);

    if (waypoint) {
        waypoint.destroy();
        // console.log('destroy waypoint instance');
    }
    paginatedFunctions[key](
        false,
        $(this).attr('offset')
    );
});

/**
 * Init a waypoint for paginate next
 */
function initPaginateNextWaypoint() {
    // console.log('initTimelinePaginateNextWaypoint');
    // console.log('create waypoint instance');

    var waypoints = $('#moreResults').waypoint({
        handler: function(direction) {
            // console.log('Hit moreResults');
            // console.log(direction);

            if (direction == 'down') {
                $("[action='listingNext']").trigger( "click", this );
            }
        },
        offset: 'bottom-in-view'
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
    filterDate = $('.pseudoTabs .currentPage').attr('filter');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(filterDate);

    var xhrPath = getXhrPath(
        ROUTE_TAG_LISTING,
        'document',
        'documentsByTag',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'uuid': uuid, 'filterDate': filterDate, 'offset': offset},
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
    filterDate = $('.pseudoTabs .currentPage').attr('filter');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(filterDate);

    var xhrPath = getXhrPath(
        ROUTE_ORGANIZATION_LISTING,
        'document',
        'documentsByOrganization',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'uuid': uuid, 'filterDate': filterDate, 'offset': offset},
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
