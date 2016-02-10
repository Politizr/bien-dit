/**
 * Loading of top "tag" listing.
 * @param targetElement
 * @param localLoader
 */
function topTagListing(targetElement, localLoader) {
    // console.log('*** topTagListing');
    // console.log(targetElement);
    // console.log(localLoader);
    
    // Tag form filter
    var datas = $('#tagFilter').serializeArray();
    // console.log(datas);
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'tagFilterDate[]', value: 'lastMonth'});
    }
    // console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_TAG_LISTING_TOP,
        'tag',
        'topTags',
        RETURN_HTML
    );

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
                targetElement.html(data['html']);
            }
            localLoader.hide();
        }
    });
}

/**
 * Loading of user "tag" listing.
 * @param targetElement
 * @param localLoader
 */
function userTagListing(targetElement, localLoader) {
    // console.log('*** topTagListing');
    // console.log(targetElement);
    // console.log(localLoader);
    
    var xhrPath = getXhrPath(
        ROUTE_TAG_LISTING_USER,
        'tag',
        'userTags',
        RETURN_HTML
    );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                targetElement.html(data['html']);
            }
            localLoader.hide();
        }
    });
}

