

// change checkbox type event
$('#formTagType :checkbox').on('change', function() {
    // @todo
});



/**
 *
 */
function publishDebate(uuid)
{
    console.log('*** publishDebate');
    console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_PUBLISH,
        'document',
        'debatePublish',
        RETURN_URL
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#ajaxGlobalLoader').hide();
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            // redirection
            window.location = data['redirectUrl'];
        }
    });
}


/**
 *
 */
function publishReaction(uuid)
{
    console.log('*** publishReaction');
    console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_REACTION_PUBLISH,
        'document',
        'reactionPublish',
        RETURN_URL
        );
    
    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#ajaxGlobalLoader').hide();
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            // redirection
            window.location = data['redirectUrl'];
        }
    });
}

