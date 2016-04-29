// beta

/**
 * Note +/- debate or reaction or comment
 * @param context
 * @param localLoader
 * @param uuid
 * @param type
 * @param way
 */
function noteDocument(context, localLoader, uuid, type, way) {
    // console.log('*** note');
    // console.log(context);
    // console.log(localLoader);
    // console.log(uuid);
    // console.log(type);
    // console.log(way);
    
    var xhrPath = getXhrPath(
        ROUTE_NOTE,
        'document',
        'note',
        RETURN_HTML
    );

    return xhrCall(
        context.closest('.notation'),
        { 'uuid': uuid, 'type': type, 'way': way },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $(this).html(data['html']);

            // update reputation counter
            scoreCounter();
            badgesCounter();
        }
        localLoader.hide();
    });
}
