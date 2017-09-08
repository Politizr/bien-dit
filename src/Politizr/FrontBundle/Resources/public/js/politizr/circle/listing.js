// beta
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_TOPIC] = documentsByTopicListing;


/**
 * Loading of paginated filters/search listing.
 * @param targetElement
 * @param localLoader
 */
function publicationsByTopicListing(init, offset) {
    console.log('*** publicationsByTopicListing');
    console.log(init);
    console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;

    targetElement = $('#documentListing .listTop');
    localLoader = $('#documentListing').find('.ajaxLoader').first();

    console.log(targetElement);
    console.log(localLoader);

    // datas = getCurrentPublicationFilters();
    datas.push({name: 'offset', value: offset});

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
 * @todo
 */
function getCurrentPublicationFilters() {
    console.log('*** getCurrentPublicationFilters');

    var filters = [];

    // map
    if ($('.mapBreadcrumbs').find('.current').length) {
        console.log('sub map uuid');
        uuid = $('.mapBreadcrumbs').find('.current').attr('uuid');
        console.log(uuid);
        type = $('.mapBreadcrumbs').find('.current').attr('type');
        console.log(type);
    } else {
        console.log('map uuid');
        uuid = $('.mapMenu').find('.active').attr('uuid');
        console.log(uuid);
        type = $('.mapMenu').find('.active').attr('type');
        console.log(type);
    }

    filters.push({name: 'geoUuid', value: uuid});
    filters.push({name: 'type', value: type});
    
    // publication
    filters.push({name: 'filterPublication', value: $('#publicationFilter input:checked').val()});

    // profile
    filters.push({name: 'filterProfile', value: $('#profileFilter input:checked').val()});

    // activity
    filters.push({name: 'filterActivity', value: $('#activityFilter input:checked').val()});

    // date
    filters.push({name: 'filterDate', value: $('#dateFilter input:checked').val()});

    console.log(filters);
    return filters;
}

