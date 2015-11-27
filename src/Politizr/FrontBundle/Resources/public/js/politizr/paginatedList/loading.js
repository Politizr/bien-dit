// Gestion du type débat / user
$("body").on("change", ".type", function(e) {
    // console.log('*** change type');

    defaultOrderFilters = $('#defaultOrderFilters').serializeArray();
    // console.log(defaultOrderFilters);

    // réinitialisation des filtres & de la liste
    initListing($('#paginatedList').attr('withFilters'), defaultOrderFilters );
});

// Gestion de l'ordonnancement
$("body").on("change", ".order", function(e) {
    e.preventDefault();
    listing();
});

// Gestion des filtres
$("body").on("change", ".filter", function(e) {
    e.preventDefault();
    listing();
});

// Page suivante
$("body").on("click", "[action='paginateNext']", function(e, waypoint) {
    // console.log('paginate next');
    if (waypoint) {
        waypoint.destroy();
        // console.log('destroy waypoint instance');
    }
    listing(false, $(this).attr('offset'));
});

//
$("body").on("postFollowDebateEvent", function(event, way) {
    // console.log('*** postFollowDebateEvent');
    modalType = $('#modalBoxContent').attr('class');

    // @todo JS use constant
    if (modalType == 'modalSubscriptions') {
        // console.log('modal subscriptions');
        // dynamicaly offset updating
        if ($('#moreResults').attr('offset')) {
            var offset = parseInt($('#moreResults').attr('offset'));
            if (way == 'unfollow') {
                offset = offset - 1;
            } else {
                offset = offset + 1;
            }
            // console.log(offset);
            $('#moreResults').attr('offset', offset);
        }
    }
});

/**
 * Init a waypoint for paginate next
 */
function initPaginateNextWaypoint() {
    // console.log('initPaginateNextWaypoint');
    // console.log('create waypoint instance');

    var waypoints = $('#moreResults').waypoint({
        handler: function(direction) {
            // console.log('Hit moreResults');
            // console.log(direction);
    
            if (direction == 'down') {
                $("[action='paginateNext']").trigger( "click", this );
            }
        },
        context: '#listContent',
        offset: 'bottom-in-view'
    }); 
}

/**
 * Init filters and first listing loading
 *
 * @param string withFilters  true | false
 * @param array defaultOrderFilters serialized array of order & filters
 */
function initListing(withFilters, defaultOrderFilters) {
    // console.log('*** initListing');
    // console.log(withFilters);
    // console.log(defaultOrderFilters);

    withFilters = (typeof withFilters === "undefined") ? 'true' : withFilters;

    $('#listContent').html('');

    if ('false' == withFilters) {
        // initialisation de la liste
        listing();
    } else {
        var defaultType = $('#listType input:checked').val();
        // console.log(defaultType);

        var xhrPath = getXhrPath(
            ROUTE_MODAL_FILTERS,
            'modal',
            'filters',
            RETURN_HTML
            );

        // chargement des filtres associé au type
        $.ajax({
            type: 'POST',
            url: xhrPath,
            data: { 'defaultType': defaultType, 'defaultOrderFilters': defaultOrderFilters },
            dataType: 'json',
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
            statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
            success: function(data) {
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    $('#listOrder').html(data['listOrder']);
                    $('#listFilter').html(data['listFilter']);

                    listing();
                }
            }
        });
    }
}

/**
 * Paginated loading of listing.
 *
 * @param boolean init
 * @param string offset
 */
function listing(init, offset) {
    // console.log('*** listing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;
    
    // Récupération de l'ordonnancement en cours
    var order = $('#listOrder').serializeArray();
    // console.log(order);

    // Récupération du form des filtres
    var filters = $('#listFilter').serializeArray();
    // console.log(filters);

    // Concaténation des attributs
    var datas = $.merge(order, filters);
    datas.push({name: 'offset', value: offset});

    // Récupération d'arguments JS supplémentaires
    var additionalDatas = $('#paginatedList').data();
    $.each(additionalDatas, function(index, element) {
        datas.push({name: index, value: element});
    });
    // console.log(datas);

    // Récupération de l'URL de la liste
    var url = $('#listType input:checked').attr('url');

    $.ajax({
        type: 'POST',
        url: url,
        data: datas,
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#modalScrollNav').remove();
                if (init) {
                    $('#listContent').html(data['html']);
                } else {
                    $('#listContent').append(data['html']);
                }

                // Waypoint for infinite scrolling 
                // initPaginateNextWaypoint();

                // maj DOM onSuccess
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });    
}
