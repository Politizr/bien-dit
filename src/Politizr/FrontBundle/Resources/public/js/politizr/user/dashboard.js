// on document ready
$(function() {
    topListing();
});

// Gestion des filtres
$("body").on("change", ".mapFilter", function(e) {
    // console.log('*** change filter');

    e.preventDefault();
    topListing($('#listFilter').attr('uuid'));
});

$("body").on("click", "[action='mapZoom']", function() {
    // console.log('*** mapZoom');

    topListing($(this).attr('uuid'));
});

/**
 * Loading of "top" listing.
 *
 * @param string geoTagUuid
 */
function topListing(geoTagUuid) {
    // console.log('*** topListing');
    // console.log(geoTagUuid);
    
    geoTagUuid = (typeof geoTagUuid === "undefined") ? null : geoTagUuid;
    
    // Récupération du form des filtres
    var datas = $('#listFilter').serializeArray();
    // console.log(datas);
    // @todo hack to fix / why the form is not well serialized at the 1st call
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'filterDate[]', value: 'lastWeek'});
    }

    // Push additional arguments
    datas.push({name: 'geoTagUuid', value: geoTagUuid});
    // console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_MAP,
        'dashboard',
        'map',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
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
                $('.dbMap').html(data['html']);

                // maj DOM onSuccess
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });    
}
