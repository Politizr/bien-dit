/**
 * Update map menu (france / france outre mer)
 * /!\ only used w. shorcut 'my region' / 'my deparment' / 'my city'
 *
 * @param uuid
 */
function mapMenu(uuid) {
    // console.log('*** mapMenu');
    // console.log(uuid);

    localLoader = $('#mapMenu').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_MAP_BREADCRUMB,
        'tag',
        'mapMenu',
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
            $('#mapMenu').html(data['html']);
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
