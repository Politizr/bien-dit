// beta
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_TOPIC] = publicationsByTopicListing;


/**
 * Loading of paginated filters/search listing.
 * @param init
 * @param offset
 */
function publicationsByTopicListing(init, offset) {
    // console.log('*** publicationsByTopicListing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();
    topicUuid = $('#documentListing').attr('uuid');

    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(topicUuid);

    datas = getCurrentTopicFilters();
    datas.push({name: 'offset', value: offset});
    datas.push({name: 'topicUuid', value: topicUuid});

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_FILTERS,
        'circle',
        'publicationsByTopic',
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
function getCurrentTopicFilters() {
    // console.log('*** getCurrentTopicFilters');

    var filters = [];

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

