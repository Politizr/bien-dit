// beta

/**
 * Load Facebook insights
 */
function loadFbInsights() {
    // console.log('*** loadFbInsights');

    var targetElement = $('.statFB');
    var localLoader = $('.statFB').find('.ajaxLoader').first();
    var uuid = $('.statFB').attr('uuid');
    var type = $('.statFB').attr('type');

    return fbInsights(targetElement, localLoader, uuid, type);
}

/**
 * Load Facebook comments
 */
function loadFbComments() {
    // console.log('*** loadFbComments');

    var targetElement = $('.embedFB');
    var localLoader = $('.embedFB').find('.ajaxLoader').first();
    var uuid = $('.embedFB').attr('uuid');
    var type = $('.embedFB').attr('type');

    return fbComments(targetElement, localLoader, uuid, type);
}

/**
 * Bookmark
 */
function bookmark(targetElement, localLoader, uuid, type) {
    // console.log('*** bookmark');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_BOOKMARK,
        'document',
        'bookmark',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * FB Insights
 */
function fbInsights(targetElement, localLoader, uuid, type) {
    // console.log('*** fbInsights');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_BOOKMARK,
        'document',
        'facebookInsights',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * FB Comments
 */
function fbComments(targetElement, localLoader, uuid, type) {
    // console.log('*** fbComments');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_BOOKMARK,
        'document',
        'facebookComments',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}

/**
 * Boost question
 */
function boostQuestion(targetElement, localLoader, uuid, type, boost) {
    // console.log('*** boostQuestion');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_BOOKMARK,
        'document',
        'boostQuestion',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type, 'boost': boost },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}
