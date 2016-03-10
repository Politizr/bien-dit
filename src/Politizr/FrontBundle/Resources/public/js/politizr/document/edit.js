// beta

function triggerSaveDocument()
{
    console.log('*** triggerSaveDocument');

    var documentSave = $('.actionSave').find('a').attr('action');
    $('[action="'+documentSave+'"]').trigger('click');
}

/**
 *
 */
function saveDebate()
{
    console.log('*** saveDebate');

    var description = descriptionEditor.serialize();
    console.log(description['element-0']['value']);

    $('#debate_description').val(description['element-0']['value']);

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_UPDATE,
        'document',
        'debateUpdate',
        RETURN_BOOLEAN
        );

    return xhrCall(
        document,
        $("#formDebateUpdate").serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('.actionSave').addClass('saved');
            }
        }
        localLoader.hide();
    });
}

/**
 *
 */
function saveReaction()
{
    console.log('*** saveDebate');

    var description = descriptionEditor.serialize();
    console.log(description['element-0']['value']);

    $('#reaction_description').val(description['element-0']['value']);

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_REACTION_UPDATE,
        'document',
        'reactionUpdate',
        RETURN_BOOLEAN
        );
    
    return xhrCall(
        document,
        $("#formReactionUpdate").serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('.actionSave').addClass('saved');
            }
        }
        localLoader.hide();
    });
}

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
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['error']) {
                $('#ajaxGlobalLoader').hide();
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // redirection
                window.location = data['redirectUrl'];
            }
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
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['error']) {
                $('#ajaxGlobalLoader').hide();
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // redirection
                window.location = data['redirectUrl'];
            }
        }
    });
}

/**
 *
 */
function deleteDebate(uuid)
{
    console.log('*** deleteDebate');
    console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_DELETE,
        'document',
        'debateDelete',
        RETURN_URL
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['error']) {
                $('#ajaxGlobalLoader').hide();
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // redirection
                window.location = data['redirectUrl'];
            }
        }
    });
}

/**
 *
 */
function deleteReaction(uuid)
{
    console.log('*** deleteReaction');
    console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_REACTION_DELETE,
        'document',
        'reactionDelete',
        RETURN_URL
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['error']) {
                $('#ajaxGlobalLoader').hide();
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // redirection
                window.location = data['redirectUrl'];
            }
        }
    });
}

