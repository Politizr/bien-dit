// Gestion du type débat / user
$("body").on("change", ".type", function(e) {
    // maj de l'url cible de la liste
    $('#paginatedList').attr('url', $(this).attr('url'));
    
    // réinitialisation des filtres & de la liste
    initListing($(this).val(), $('#paginatedList').attr('withFilters'));
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
$("body").on("click", "[action='paginateNext']", function(e) {
    listing(false, $(this).attr('offset'));
});

//
$("body").on("postFollowDebateEvent", function(event, subjectId, way) {
    // console.log('*** postFollowDebateEvent');
    modalType = $('#modalBoxContent').attr('class');

    // @todo JS use constant
    if (modalType == 'subscriptions') {
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
 * Init filters and first listing loading
 *
 * @param string type   debate | user
 * @param string withFilters  true | false
 */
function initListing(type, withFilters) {
    // console.log('*** initListing');
    // console.log(type);
    // console.log(withFilters);

    type = (typeof type === "undefined") ? 'debate' : type;
    withFilters = (typeof withFilters === "undefined") ? 'true' : withFilters;

    $('#listContent').html('');

    if ('false' == withFilters) {
        // initialisation de la liste
        listing();
    } else {
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
            data: { 'type': type },
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
 * @param string init   key "debate" | "user"
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
    // console.dir(order);

    // Récupération du form des filtres
    var filters = $('#listFilter').serializeArray();
    // console.dir(filters);

    // Concaténation des attributs
    var datas = $.merge(order, filters);
    datas.push({name: 'offset', value: offset});

    // Récupération d'arguments JS supplémentaires
    var additionalDatas = $('#paginatedList').data();
    $.each(additionalDatas, function(index, element) {
        datas.push({name: index, value: element});
    });

    // console.dir(datas);

    // Récupération de l'URL de la liste
    var url = $('#paginatedList').attr('url');

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
                $('#scrollNav').remove();
                if (init) {
                    $('#listContent').html(data['html']);
                } else {
                    $('#listContent').append(data['html']);
                }

                // maj DOM onSuccess
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });    
}
