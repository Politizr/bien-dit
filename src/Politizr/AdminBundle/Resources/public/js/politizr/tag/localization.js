$(function() {
    var tagId = $('#tagId').val();
    initTagLocalization(tagId);
});

// search localizations
$("body").on("click", "a[action='searchLocalizations']", function(e) {
    console.log('click searchLocalizations');

    var form = $(this).closest('form');

    searchTagLocalizations(form);
});

// add tag city
$("body").on("click", "a[action='addTagCity']", function(e) {
    console.log('click addTagCity');

    var tagId = $(this).attr('tagId');
    var cityId = $(this).attr('cityId');
    console.log(tagId);
    console.log(cityId);

    var item = $(this).closest('.cityTagItem');

    $.when(
        addTagLocalization(tagId, cityId)
    ).done(function(r1) {
        item.remove();
        initTagLocalization(tagId);
    });
});

// delete tag city
$("body").on("click", "a[action='deleteTagCity']", function(e) {
    console.log('click deleteTagCity');

    var tagId = $(this).attr('tagId');
    var cityId = $(this).attr('cityId');
    console.log(tagId);
    console.log(cityId);

    var item = $(this).closest('.cityTagItem');

    $.when(
        deleteTagLocalization(tagId, cityId)
    ).done(function(r1) {
        item.remove();
        $(this).closest('.cityTagItem').remove();
    });
});

/**
 * Init current tag localizations
 */
function initTagLocalization(tagId) {
    console.log('*** initTagLocalization');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_TAG_CITIES,
        'admin',
        'getCitiesByTagId',
        RETURN_HTML
    );

    return xhrCall(
        document,
        {'tagId': tagId },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#selectedCities').html(data['html']);
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Search tag localizations
 */
function searchTagLocalizations(form) {
    console.log('*** searchLocalizations');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_TAG_CITIES_SEARCH_LISTING,
        'admin',
        'getCitiesByInsee',
        RETURN_HTML
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#searchResult').html(data['html']);
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Add tag localizations
 */
function addTagLocalization(tagId, cityId) {
    console.log('*** addTagLocalization');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_ADD_TAG_CITY,
        'admin',
        'addTagCityRelation',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        {'tagId': tagId, 'cityId': cityId },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Delete tag localizations
 */
function deleteTagLocalization(tagId, cityId) {
    console.log('*** deleteTagLocalization');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_DELETE_TAG_CITY,
        'admin',
        'deleteTagCityRelation',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        {'tagId': tagId, 'cityId': cityId },
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#ajaxGlobalLoader').hide();
    });
}

