// on document ready
$(function() {
    $('#ajaxGlobalLoader').show();

    topListing();
    tagListing();

    $('#ajaxGlobalLoader').hide();
});

// Map filter change
$("body").on("change", ".mapFilter", function(e) {
    console.log('*** change mapFilter');
    e.preventDefault();

    topListing($('#mapFilter').attr('uuid'));
});

$("body").on("click", "[action='mapZoom']", function() {
    console.log('*** mapZoom');

    topListing($(this).attr('uuid'));
});

// Tag filter change
$("body").on("change", ".tagFilter", function(e) {
    console.log('*** change tagFilter');
    e.preventDefault();

    tagListing();
});


/**
 * Update link with current filter selected attribute
 */
function updateSuiteLink() {
    console.log('*** updateSuiteLink');

    // update "suite" link attributes
    console.log($('#mapFilter input:checked').val());
    $('#modalMoreDebates').attr('defaultFilterDate', $('#mapFilter input:checked').val());    
}

/**
 * Loading of "top" map listing.
 *
 * @param string geoTagUuid
 */
function topListing(geoTagUuid) {
    console.log('*** topListing');
    console.log(geoTagUuid);
    
    geoTagUuid = (typeof geoTagUuid === "undefined") ? null : geoTagUuid;
    
    // Récupération du form des filtres
    var datas = $('#mapFilter').serializeArray();
    console.log(datas);
    // @todo hack to fix / why the form is not well serialized at the 1st call
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'filterDate[]', value: 'allDate'});
    }

    // Push additional arguments
    datas.push({name: 'geoTagUuid', value: geoTagUuid});
    console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_MAP,
        'dashboard',
        'map',
        RETURN_HTML
        );

    var localLoader = $('.dbMap').children('.ajaxLoader').first();
    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: datas,
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('.dbMap').html(data['html']);

                updateSuiteLink();
                fullImgLiquid();
            }
            localLoader.hide();
        }
    });    
}

/**
 * Loading of "tag" listing.
 */
function tagListing() {
    console.log('*** tagListing');
    
    // Récupération du form des filtres
    var datas = $('#tagFilter').serializeArray();
    console.log(datas);
    // @todo hack to fix / why the form is not well serialized at the 1st call
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'filterDate[]', value: 'allDate'});
    }

    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_TAG,
        'dashboard',
        'tag',
        RETURN_HTML
        );

    var localLoader = $('.dbPopularTag').children('.ajaxLoader').first();
    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: datas,
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('.dbPopularTag').html(data['html']);
            }
            localLoader.hide();
        }
    });    
}
