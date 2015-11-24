// on document ready
$(function() {
    topListing();
});

// Gestion des filtres
$("body").on("change", ".filter", function(e) {
    console.log('*** change filter');

    e.preventDefault();
    topListing($('#listFilter').attr('uuid'));
});

$("body").on("click", "[action='mapZoom']", function() {
    console.log('*** mapZoom');

    topListing($(this).attr('uuid'));
});

/**
 * Loading of "top" listing.
 *
 * @param string geoTagUuid
 */
function topListing(geoTagUuid) {
    console.log('*** topListing');
    console.log(geoTagUuid);
    
    geoTagUuid = (typeof geoTagUuid === "undefined") ? null : geoTagUuid;
    
    // Récupération du form des filtres
    var filters = $('#listFilter').serializeArray();
    console.dir(filters);

    // Push additional arguments
    var datas = [];
    datas.push({name: 'geoTagUuid', value: geoTagUuid});

    console.dir(datas);

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
