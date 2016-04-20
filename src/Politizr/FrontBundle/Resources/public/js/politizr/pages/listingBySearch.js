// beta
$(function() {
    publicationsByFiltersListing();
    
    stickySidebar();
});

// Map selection
$("body").on("click", "[action='map']", function() {
    console.log('*** click map');
    uuid = $(this).attr('uuid');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    
    // update breadcrumb
    mapBreadcrumb(uuid);

    // update map
    mapSchema(uuid);

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

$("body").on("click", "[action='mapZoom']", function() {
    console.log('*** click map zoom');

    uuid = $(this).attr('uuid');
    
    // update breadcrumb
    mapBreadcrumb(uuid);

    // update map
    mapSchema(uuid);

    // listing
    return publicationsByFiltersListing();
});

// Publication filter change
$("body").on("change", ".publicationFilter", function() {
    console.log('*** change publicationFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

// Profile filter change
$("body").on("change", ".profileFilter", function() {
    console.log('*** change profileFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

// Activity filter change
$("body").on("change", ".activityFilter", function() {
    console.log('*** change activityFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

// Date filter change
$("body").on("change", ".dateFilter", function() {
    console.log('*** change dateFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});


/**
 * Update map breadcrumb
 *
 * @param uuid
 */
function mapBreadcrumb(uuid) {
    console.log('*** mapBreadcrumb');
    console.log(uuid);

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
    console.log('*** mapSchema');
    console.log(uuid);

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
