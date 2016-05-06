// beta
$(function() {
    publicationsByFiltersListing();
    
    stickySidebar();
});


// Publication filter change
$("body").on("change", ".categoryFilter", function() {
    // console.log('*** change categoryFilter');

    $.when(
        reloadFilters()
    ).done(function(r1) {
        $('#documentListing .listTop').html('');
        $("[action='goUp']").trigger("click");
        return filtersListing();
    });
});

// Map selection
$("body").on("click", "[action='map']", function() {
    // console.log('*** click map');
    uuid = $(this).attr('uuid');

    $("[action='goUp']").trigger("click");

    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    
    $.when(
        // update breadcrumb
        mapBreadcrumb(uuid),
        // update map
        mapSchema(uuid)
    ).done(function(r1, r2) {
        $('#documentListing .listTop').html('');
        return filtersListing();
    });
});

// Publication filter change
$("body").on("change", ".publicationFilter", function() {
    // console.log('*** change publicationFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return filtersListing();
});

// Profile filter change
$("body").on("change", ".profileFilter", function() {
    // console.log('*** change profileFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return filtersListing();
});

// Activity filter change
$("body").on("change", ".activityFilter", function() {
    // console.log('*** change activityFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return filtersListing();
});

// Date filter change
$("body").on("change", ".dateFilter", function() {
    // console.log('*** change dateFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return filtersListing();
});

/**
 * Check selecteed category to call right filter listing function
 */
function filtersListing() {
    // console.log('*** filtersListing');

    var category = $('#categoryFilter input:checked').val();
    // console.log(category);
    if (category == 'user') {
        return usersByFiltersListing();
    } else if (category == 'publication') {
        return publicationsByFiltersListing();
    }
}

/**
 * Reload filters depending of selected category
 */
function reloadFilters() {
    // console.log('*** reloadFilters');

    localLoader = $('.sidebarSearchFilters').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_LISTING_FILTERS_CATEGORY,
        'document',
        'reloadFilters',
        RETURN_HTML
    );

    // activity
    var filters = [];
    filters.push({name: 'filterCategory', value: $('#categoryFilter input:checked').val()});

    return xhrCall(
        document,
        filters,
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.sidebarSearchFilters').html(data['html']);
        }
        localLoader.hide();
    });
}

/**
 * Update map breadcrumb
 *
 * @param uuid
 */
function mapBreadcrumb(uuid) {
    // console.log('*** mapBreadcrumb');
    // console.log(uuid);

    localLoader = $('#mapBreadcrumb').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_MAP_BREADCRUMB,
        'tag',
        'mapBreadcrumb',
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
            $('#mapBreadcrumb').html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * Update map schema
 *
 * @param uuid
 */
function mapSchema(uuid) {
    // console.log('*** mapSchema');
    // console.log(uuid);

    localLoader = $('#mapHolder').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_MAP_SCHEMA,
        'tag',
        'mapSchema',
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
            $('#mapHolder').find('.svg').html(data['html']);
        }
        $('#mapHolder').find('.ajaxLoader').first().hide();
    });
}
