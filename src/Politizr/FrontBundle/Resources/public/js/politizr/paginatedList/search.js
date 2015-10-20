// Search
$("body").on("submit", "#searchFormId", function(e) {
    // console.log('*** submit searchForm');
    e.preventDefault();
    initSearchListingByTags();
});

// Page suivante
$("body").on("click", "[action='paginateSearchNext']", function(e) {
    searchListingByTags(false, $(this).attr('offset'));
});

// Close modal, purge search session
$("body").on("click", "[action='searchModalClose']", function() {
    // console.log('*** click searchModalClose');

    // @todo fix trigger doesnt work
    // $("[action='modalClose']").trigger("click");
    $('body').removeClass('noscroll');
    $('#modalBoxContent').html('');
    $(this).closest('.modal').hide();
    $(".modalLeftCol, .modalRightCol").removeClass('activeMobileModal'); /* for mobile purpose */ 

    var xhrPath = getXhrPath(
        ROUTE_SEARCH_TAG_CLEAR_SESSION,
        'search',
        'clearSession',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            }
        }
    });
});

/**
 * Load paginated list struct (type + filters) and throws the search
 */
function initSearchListingByTags() {
    // console.log('*** initSearchListingByTags');
    // console.log($('.type[value="debate"]').attr('url'));

    // update target listing to debate type
    $('#paginatedList').attr('url', $('.type[value="debate"]').attr('url'));

    var xhrPath = getXhrPath(
        ROUTE_MODAL_SEARCH_INIT_FILTERS_LIST,
        'modal',
        'loadSearchFiltersList',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#paginatedList').html(data['paginatedList']);
                searchListingByTags();
            }
        }
    });
}

/**
 * Paginated loading of listing.
 *
 * @param string init   key "debate" | "user"
 * @param string offset
 */
function searchListingByTags(init, offset) {
    // console.log('*** listing');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;
    
    // Récupération de l'ordonnancement en cours
    var order = $('#listOrder').serializeArray();
    console.dir(order);

    // Récupération du form des filtres
    var filters = $('#listFilter').serializeArray();
    console.dir(filters);

    // Concaténation des attributs
    var datas = $.merge(order, filters);
    datas.push({name: 'offset', value: offset});

    // Récupération d'arguments JS supplémentaires
    var additionalDatas = $('#paginatedList').data();
    $.each(additionalDatas, function(index, element) {
        datas.push({name: index, value: element});
    });

    console.dir(datas);

    // Récupération de l'URL de la liste
    var url = $('#paginatedList').attr('url');
    // console.log(url);

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
