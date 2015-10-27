// ***************************************** //
//                  MODAL BOX
// ***************************************** //

// modal reputation
$("body").on("click", "[action='modalReputation']", function() {
    console.log('*** modalReputation');
    $('#modalBox').removeClass().addClass('reputation');
    modalLoading();
    loadReputation();
});


// modal abuse
$("body").on("click", "[action='modalAbuse']", function() {
    console.log('*** modalAbuse');
    $('#modalBox').removeClass().addClass('formAbuse');
    modalLoading();

    var subjectId = $(this).attr('subjectId');
    var type = $(this).attr('type');

    console.log(subjectId);
    console.log(type);

    loadAbuseBox(subjectId, type);
});

// ***************************************** //
//             MODAL BOX CLASSIC
// ***************************************** //

// modal ranking
$("body").on("click", "[action='modalRanking']", function() {
    console.log('*** modalRanking');
    modalLoading();
    loadPaginatedList('_ranking.html.twig', 'true');
});

// modal suggestions
$("body").on("click", "[action='modalSuggestions']", function() {
    console.log('*** modalSuggestions');
    modalLoading();
    loadPaginatedList('_suggestions.html.twig', 'false');
});

// modal tag
$("body").on("click", "[action='modalTagged']", function() {
    console.log('*** modalTagged');
    modalLoading();

    loadPaginatedList('_tagged.html.twig', 'true', $(this).attr('model'), $(this).attr('slug'));
});

// modal organisation
$("body").on("click", "[action='modalOrganization']", function() {
    console.log('*** modalOrganization');
    modalLoading();

    loadPaginatedList('_organization.html.twig', 'true', $(this).attr('model'), $(this).attr('slug'));
});

// modal subscriptions
$("body").on("click", "[action='modalSubscriptions']", function() {
    console.log('*** modalSubscriptions');
    modalLoading();

    loadPaginatedList('_subscriptions.html.twig', 'true');
});

// modal followers
$("body").on("click", "[action='modalFollowers']", function() {
    console.log('*** modalFollowers');
    modalLoading();

    loadPaginatedList('_followers.html.twig', 'true', $(this).attr('model'), $(this).attr('slug'));
});

// modal search
$("body").on("click", "[action='modalSearch']", function() {
    console.log('*** modalSearch');
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
    $('html, body').scrollTop( 0 );
    $(".modalRightCol").addClass('activeMobileModal');
};

/**
 *  Change modal's close action
 */
function updateCloseModalActions(action)
{
    console.log('*** updateCloseModalActions');
    console.log(action);
    if (typeof action === "undefined") {
        return false;
    }

    $('#modalHeader').children('.headerLogo').first().attr('action', action);
    $('#modalHeader').children('.modalClose').first().attr('action', action);
}

/**
 * Load search form
 *
 * @param string twigTemplate
 */
function loadSearchForm() {
    console.log('*** loadSearchForm');

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
 * Load paginated list
 *
 * @param string twigTemplate
 * @param string withFilters  true | false
 * @param string model ModelQuery to search in
 * @param string slug  ModelQuery findBySlug attribute
 */
function loadPaginatedList(twigTemplate, withFilters, model, slug) {
    console.log('*** loadPaginatedList');
    console.log(twigTemplate);

    if (typeof twigTemplate === "undefined") {
        return false;
    }
    withFilters = (typeof withFilters === "undefined") ? 'true' : withFilters;

    var xhrPath = getXhrPath(
        ROUTE_MODAL_PAGINATED_LIST,
        'modal',
        'modalPaginatedList',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'twigTemplate': twigTemplate, 'model': model, 'slug': slug },
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
                initListing('debate', withFilters);
            }
        }
    });
}

/**
 * Reputation modal loading
 */
function loadReputation() {
    console.log('*** loadReputation');

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
function loadAbuseBox(subjectId, type) {
    console.log('*** loadAbuseBox');
    subjectId = (typeof subjectId === "undefined") ? null : subjectId;
    type = (typeof type === "undefined") ? null : type;

    if (subjectId == null || type == null) {
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
        data: { 'subjectId': subjectId, 'type': type },
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
