/**
 * Update map menu (france / france outre mer)
 * /!\ only used w. shorcut 'my region' / 'my deparment' / 'my city'
 *
 * @param uuid
 * @param type
 */
function mapMenu(uuid, type) {
    // console.log('*** mapMenu');
    // console.log(uuid);
    // console.log(type);

    localLoader = $('#mapMenu').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_MAP_BREADCRUMB,
        'localization',
        'mapMenu',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
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
 * @param type
 */
function mapBreadcrumb(uuid, type) {
    // console.log('*** mapBreadcrumb');
    // console.log(uuid);
    // console.log(type);

    localLoader = $('#mapBreadcrumb').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_MAP_BREADCRUMB,
        'localization',
        'mapBreadcrumb',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
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
 * @param type
 */
function mapSchema(uuid, type) {
    // console.log('*** mapSchema');
    // console.log(uuid);
    // console.log(type);

    localLoader = $('#mapHolder').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_MAP_SCHEMA,
        'localization',
        'mapSchema',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
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
