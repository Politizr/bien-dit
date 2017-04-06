$(function() {
    var operationId = $('#operationId').val();
    initOperationLocalization(operationId);
});

// search localizations
$("body").on("click", "a[action='searchLocalizations']", function(e) {
    console.log('click searchLocalizations');

    var operationId = $(this).closest('#selectCities').find('input[name="operationId"]').val();
    var codeInsee = $(this).closest('#selectCities').find('input[name="codeInsee"]').val();
    console.log(operationId);
    console.log(codeInsee);

    searchOperationLocalizations(operationId, codeInsee);
});

// add operation city
$("body").on("click", "a[action='addOperationCity']", function(e) {
    console.log('click addOperationCity');

    var operationId = $(this).attr('operationId');
    var cityId = $(this).attr('cityId');
    console.log(operationId);
    console.log(cityId);

    var item = $(this).closest('.cityOperationItem');

    $.when(
        addOperationLocalization(operationId, cityId)
    ).done(function(r1) {
        item.remove();
        initOperationLocalization(operationId);
    });
});

// delete operation city
$("body").on("click", "a[action='deleteOperationCity']", function(e) {
    console.log('click deleteOperationCity');

    var operationId = $(this).attr('operationId');
    var cityId = $(this).attr('cityId');
    console.log(operationId);
    console.log(cityId);

    var item = $(this).closest('.cityOperationItem');

    $.when(
        deleteOperationLocalization(operationId, cityId)
    ).done(function(r1) {
        item.remove();
        $(this).closest('.cityOperationItem').remove();
    });
});

/**
 * Init current operation localizations
 */
function initOperationLocalization(operationId) {
    console.log('*** initOperationLocalization');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_OPERATION_CITIES,
        'admin',
        'getCitiesByOperationId',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'operationId': operationId },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#selectedCities').html(data['html']);
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Search operation localizations
 */
function searchOperationLocalizations(operationId, codeInsee) {
    console.log('*** searchLocalizations');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_OPERATION_CITIES_SEARCH_LISTING,
        'admin',
        'getCitiesByInsee',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'operationId': operationId, 'codeInsee': codeInsee },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#searchResult').html(data['html']);
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Add operation localizations
 */
function addOperationLocalization(operationId, cityId) {
    console.log('*** addOperationLocalization');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_ADD_OPERATION_CITY,
        'admin',
        'addOperationCityRelation',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        {'operationId': operationId, 'cityId': cityId },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Delete operation localizations
 */
function deleteOperationLocalization(operationId, cityId) {
    console.log('*** deleteOperationLocalization');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_DELETE_OPERATION_CITY,
        'admin',
        'deleteOperationCityRelation',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        {'operationId': operationId, 'cityId': cityId },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#ajaxGlobalLoader').hide();
    });
}

