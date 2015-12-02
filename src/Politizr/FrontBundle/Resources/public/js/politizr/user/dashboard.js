// on document ready
$(function() {
    $('#ajaxGlobalLoader').show();

    topListing();
    tagListing();
    debateListing();
    userListing();
    geoListing();
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

// Debate filter change
$("body").on("change", ".debateFilter", function(e) {
    console.log('*** change debateFilter');
    e.preventDefault();

    debateListing();
});

// User filter change
$("body").on("change", ".userFilter", function(e) {
    console.log('*** change userFilter');
    e.preventDefault();

    userListing();
});

/**
 * Update map "suite" link with current filter selected attribute
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
        datas.push({name: 'mapFilterDate[]', value: 'allDate'});
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
        datas.push({name: 'tagFilterDate[]', value: 'allDate'});
    }
    console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_TAG,
        'dashboard',
        'topTags',
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

/**
 * Loading of "debate" listing.
 */
function debateListing() {
    console.log('*** debateListing');
    
    // Récupération du form des filtres
    var datas = $('#debateFilter').serializeArray();
    console.log(datas);
    // @todo hack to fix / why the form is not well serialized at the 1st call
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'debateFilterDate[]', value: 'allDate'});
    }
    console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_TOP_DEBATES,
        'dashboard',
        'topDebates',
        RETURN_HTML
        );

    var localLoader = $('.dbPopularDebates').children('.ajaxLoader').first();
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
                $('.dbPopularDebates').html(data['html']);
                fullImgLiquid();
            }
            localLoader.hide();
        }
    });
}

/**
 * Loading of "user" listing.
 */
function userListing() {
    console.log('*** userListing');
    
    // Récupération du form des filtres
    var datas = $('#userFilter').serializeArray();
    console.log(datas);
    // @todo hack to fix / why the form is not well serialized at the 1st call
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'userFilterDate[]', value: 'allDate'});
    }
    console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_TOP_USERS,
        'dashboard',
        'topUsers',
        RETURN_HTML
        );

    var localLoader = $('.dbPopularProfiles').children('.ajaxLoader').first();
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
                $('.dbPopularProfiles').html(data['html']);
                fullImgLiquid();
            }
            localLoader.hide();
        }
    });
}


/**
 * Loading of "geo tagged user" listing.
 */
function geoListing() {
    console.log('*** geoListing');
    
    var xhrPath = getXhrPath(
        ROUTE_DASHBOARD_GEO,
        'dashboard',
        'geoTaggedUser',
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
                $('.dbCloseToYou').html(data['html']);
                fullImgLiquid();
            }

            // last ajax call hide global loader
            $('#ajaxGlobalLoader').hide();
        }
    });
}
