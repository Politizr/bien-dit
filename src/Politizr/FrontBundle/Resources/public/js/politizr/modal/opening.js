// ***************************************** //
//                  MODAL BOX
// ***************************************** //

// modal reputation
$("body").on("click", "[action='modalReputation']", function() {
    // console.log('*** modalReputation');
    $('#modalBoxContent').removeClass().addClass('modalReputation');
    modalLoading();
    loadReputation();
});

// modal abuse
$("body").on("click", "[action='modalAbuse']", function() {
    // console.log('*** modalAbuse');
    $('#modalBoxContent').removeClass().addClass('modalFormAbuse');
    modalLoading();

    var uuid = $(this).attr('uuid');
    var type = $(this).attr('type');

    // console.log(uuid);
    // console.log(type);

    loadAbuseBox(uuid, type);
});

// modal ask for update
$("body").on("click", "[action='modalAskForUpdate']", function() {
    // console.log('*** modalAbuse');
    $('#modalBoxContent').removeClass().addClass('modalFormAskForUpdate');
    modalLoading();

    var uuid = $(this).attr('uuid');
    var type = $(this).attr('type');

    // console.log(uuid);
    // console.log(type);

    loadAskForUpdateBox(uuid, type);
});

// ***************************************** //
//             MODAL BOX CLASSIC
// ***************************************** //

// modal ranking
$("body").on("click", "[action='modalRanking']", function() {
    // console.log('*** modalRanking');
    $('#modalBoxContent').removeClass().addClass('modalRanking');
    modalLoading();
    loadPaginatedList('_ranking.html.twig', 'true');
});

// modal suggestions
$("body").on("click", "[action='modalSuggestions']", function() {
    // console.log('*** modalSuggestions');
    $('#modalBoxContent').removeClass().addClass('modalSuggestions');
    modalLoading();
    loadPaginatedList('_suggestions.html.twig', 'false');
});

// modal tag
$("body").on("click", "[action='modalTagged']", function() {
    // console.log('*** modalTagged');
    $('#modalBoxContent').removeClass().addClass('modalTagged');
    modalLoading();

    loadPaginatedList('_tagged.html.twig', 'true', $(this).attr('defaultType'), $(this).attr('defaultOrder'), $(this).attr('defaultFilterDate'), $(this).attr('defaultFilterUser'), $(this).attr('model'), $(this).attr('uuid'));
});

// modal organisation
$("body").on("click", "[action='modalOrganization']", function() {
    // console.log('*** modalOrganization');
    $('#modalBoxContent').removeClass().addClass('modalOrganization');
    modalLoading();

    loadPaginatedList('_organization.html.twig', 'true', 'user', 'mostFollowed', null, null, $(this).attr('model'), $(this).attr('uuid'));
});

// modal subscriptions
$("body").on("click", "[action='modalSubscriptions']", function() {
    // console.log('*** modalSubscriptions');
    $('#modalBoxContent').removeClass().addClass('modalSubscriptions');
    modalLoading();

    loadPaginatedList('_subscriptions.html.twig', 'true');
});

// modal followers
$("body").on("click", "[action='modalFollowers']", function() {
    // console.log('*** modalFollowers');
    $('#modalBoxContent').removeClass().addClass('modalFollowers');
    modalLoading();

    loadPaginatedList('_followers.html.twig', 'true', 'user', 'last', null, null, $(this).attr('model'), $(this).attr('uuid'));
});

// modal search
$("body").on("click", "[action='modalSearch']", function() {
    // console.log('*** modalSearch');
    $('#modalBoxContent').removeClass().addClass('modalSearch');
    modalLoading();

    updateCloseModalActions('searchModalClose');

    loadSearchForm();
});

// ***************************************** //
//       GLOBAL MODAL LOADING FUNCTIONS
// ***************************************** //

/**
 * Generic modal loading
 */
function modalLoading() {
    $('#modalBox').fadeIn('fast');
    $('body').addClass('noscroll');
    // $('html, body').scrollTop( 0 );
    $(".modalRightCol").addClass('activeMobileModal');
};

/**
 *  Change modal's close action
 */
function updateCloseModalActions(action)
{
    // console.log('*** updateCloseModalActions');
    // console.log(action);
    if (typeof action === "undefined") {
        return false;
    }

    $('#modalBox').find('.headerLogo').first().attr('action', action);
    $('#modalBox').find('.modalClose').first().attr('action', action);
}

/**
 * Load paginated list
 *
 * @param string twigTemplate
 * @param string withFilters  true | false
 * @param string defaultType debate|reaction|user default rendering checkbox listing
 * @param string defaultOrder mostFollowed|bestNote|last|mostReactions|mostComments|mostViews|mostActive default orderby listing keyword
 * @param array defaultFilterDate allDate|lastDay|lastWeek|lastMonth default filterby date listing keywords
 * @param array defaultFilterUser allUsers|qualified|citizen default filterby user listing keywords
 * @param string model ModelQuery to search in
 * @param string uuid  UUID attribute
 */
function loadPaginatedList(twigTemplate, withFilters, defaultType, defaultOrder, defaultFilterDate, defaultFilterUser, model, uuid) {
    // console.log('*** loadPaginatedList');
    // console.log(twigTemplate);
    // console.log(withFilters);
    // console.log(defaultType);
    // console.log(defaultOrder);
    // console.log(defaultFilterDate);
    // console.log(defaultFilterUser);
    // console.log(model);
    // console.log(uuid);

    if (typeof twigTemplate === "undefined") {
        return false;
    }

    withFilters = (typeof withFilters === "undefined" || withFilters === null) ? 'true' : withFilters;
    defaultType = (typeof defaultType === "undefined" || defaultType === null) ? 'debate' : defaultType;
    defaultOrder = (typeof defaultOrder === "undefined" || defaultOrder === null) ? 'last' : defaultOrder;
    defaultFilterDate = (typeof defaultFilterDate === "undefined" || defaultFilterDate === null) ? 'allDate' : defaultFilterDate;
    defaultFilterUser = (typeof defaultFilterUser === "undefined" || defaultFilterUser === null) ? 'allUsers' : defaultFilterUser;

    // transform default order & filters to serialized array
    var defaultOrderFilters = [];
    defaultOrderFilters.push({name: 'defaultOrder', value: defaultOrder});
    defaultOrderFilters.push({name: 'defaultFilterDate', value: defaultFilterDate});
    defaultOrderFilters.push({name: 'defaultFilterUser', value: defaultFilterUser});
    // console.log(defaultOrderFilters);

    var xhrPath = getXhrPath(
        ROUTE_MODAL_PAGINATED_LIST,
        'modal',
        'modalPaginatedList',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'twigTemplate': twigTemplate, 'model': model, 'uuid': uuid, 'defaultType': defaultType, 'defaultOrderFilters': defaultOrderFilters },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#modalBoxContent').html(data['html']);
                initListing(withFilters, defaultOrderFilters);
            }
        }
    });
}

/**
 * Load search form
 *
 * @param string twigTemplate
 */
function loadSearchForm() {
    // console.log('*** loadSearchForm');

    var xhrPath = getXhrPath(
        ROUTE_MODAL_PAGINATED_LIST,
        'modal',
        'loadSearchForm',
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
                $('#modalBoxContent').html(data['html']);
                initInputSearchByTags();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}

/**
 * Reputation modal loading
 */
function loadReputation() {
    // console.log('*** loadReputation');

    var xhrPath = getXhrPath(
        ROUTE_MODAL_REPUTATION,
        'user',
        'reputation',
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
                $('#modalBoxContent').html(data['html']);
                reputationCharts();
            }
        }
    });
}

/**
 * Abuse box loading
 */
function loadAbuseBox(uuid, type) {
    // console.log('*** loadAbuseBox');
    uuid = (typeof uuid === "undefined") ? null : uuid;
    type = (typeof type === "undefined") ? null : type;

    if (uuid == null || type == null) {
        return false;
    }

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ABUSE,
        'monitoring',
        'abuse',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'uuid': uuid, 'type': type },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#modalBoxContent').html(data['html']);
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}

/**
 * Ask for update box loading
 */
function loadAskForUpdateBox(uuid, type) {
    // console.log('*** loadAbuseBox');
    uuid = (typeof uuid === "undefined") ? null : uuid;
    type = (typeof type === "undefined") ? null : type;

    if (uuid == null || type == null) {
        return false;
    }

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ASK_FOR_UPDATE,
        'monitoring',
        'askForUpdate',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'uuid': uuid, 'type': type },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#modalBoxContent').html(data['html']);
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}
