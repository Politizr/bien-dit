/**
 * Loading of top "document" listing.
 * @param targetElement
 * @param localLoader
 */
function topDocumentListing(targetElement, localLoader) {
    // console.log('*** topDocumentListing');
    // console.log(targetElement);
    // console.log(localLoader);
    
    // Tag form filter
    var datas = $('#documentFilter').serializeArray();
    // console.log(datas);
    if ($.isEmptyObject(datas)) {
        datas.push({name: 'documentFilterDate[]', value: 'lastMonth'});
    }
    // console.log(datas);

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_TOP,
        'document',
        'topDocuments',
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
 * Loading of suggestion "document" listing.
 * @param targetElement
 * @param localLoader
 */
function suggestionDocumentListing(targetElement, localLoader) {
    // console.log('*** suggestionDocumentListing');
    // console.log(targetElement);
    // console.log(localLoader);
    
    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_LISTING_SUGGESTION,
        'document',
        'suggestionDocuments',
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

                // init cycle
                $('.cycle-slideshow').cycle();

                // img liquid reinit
                fullImgLiquid();
            }
            localLoader.hide();
        }
    });
}
