// beta

/**
 * Loading of organization listing.
 * @param targetElement
 * @param localLoader
 * @param currentUuid
 */
function organizationListing(targetElement, localLoader, currentUuid) {
    // console.log('*** organizationListing');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(currentUuid);
    
    var xhrPath = getXhrPath(
        ROUTE_ORGANIZATION_LISTING,
        'organization',
        'listing',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': currentUuid },
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

