// beta

/**
 * Validate direct message form
 * @param form
 */
function sendDirectMessage(form, localLoader)
{
    // console.log('*** sendDirectMessage');
    // console.log(form);
    // console.log(localLoader);

    var xhrPath = getXhrPath(
        ROUTE_PUBLIC_DIRECT_MESSAGE,
        'general',
        'directMessageSend',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Merci, nous allons reprendre contact avec vous très rapidement!');
            $('#infoBoxHolder .boxSuccess').show();
            form.trigger("reset");
        }
        localLoader.hide();
    });
}
