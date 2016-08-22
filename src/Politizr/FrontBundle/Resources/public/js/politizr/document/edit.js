// beta

// auto resize text area
autosize($('.formBlock textarea'));

/**
 * Auto save
 * Event = keyup + 2sec
 * http://stackoverflow.com/questions/9966394/can-i-delay-the-keyup-event-for-jquery
 */

/**
 *
 */
function dataRequest() {
    return triggerSaveDocument();
}

$('#debate_title, #reaction_title, #debate_copyright, #reaction_copyright, .editable.description').on('keyup', delayRequest);

/**
 *
 */
function delayRequest(ev) {
    // console.log('*** autoSaveDelay');
    $('.actionSave').removeClass('saved');

    if(delayRequest.timeout) {
        clearTimeout(delayRequest.timeout);
    }
    var target = this;
    delayRequest.timeout = setTimeout(function() {
        dataRequest.call(target, ev);
    }, 5000); // 5s
}

/**
 * Auto save
 * Event = mouseup
 */
$('.editable.description').on('dblclick', function() {
    // console.log('dblclick event');
    $('.actionSave').removeClass('saved');
    // delayRequest(this);
});

$('.editable.description').on('mouseup', function() {
    // console.log('mouseup event');
    $('.actionSave').removeClass('saved');
    // delayRequest(this);
});


function triggerSaveDocument()
{
    // console.log('*** triggerSaveDocument');

    var documentSave = $('.actionSave').find('a').attr('action');
    return $('[action="'+documentSave+'"]').trigger('click');
}


/**
 *
 */
 function deleteDocumentPhoto(uuid, type)
 {
     console.log('*** deleteDocumentPhoto');
     console.log(uuid);
     console.log(type);

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_DOCUMENT_PHOTO_DELETE,
        'document',
        'documentPhotoDelete',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        {'uuid': uuid, 'type': type},
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            // update & imgLiquid uploaded photo
            $('#uploadedPhoto').html('');
            $('.postIllustration').attr('style', '');

            $('#debate_file_name').val(null);
            $('#reaction_file_name').val(null);

            triggerSaveDocument();
        }
        localLoader.hide();
    });   
 }

/**
 *
 */
function saveDebate()
{
    // console.log('*** saveDebate');

    var description = descriptionEditor.serialize();
    // console.log(description['element-0']['value']);

    $('#debate_description').val(description['element-0']['value']);

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var serializedForms = $("#formDebateUpdate, #formTagType").serialize();

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_UPDATE,
        'document',
        'debateUpdate',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        serializedForms,
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.actionSave').addClass('saved');
        }
        localLoader.hide();
    });
}

/**
 *
 */
function saveReaction()
{
    // console.log('*** saveReaction');

    var description = descriptionEditor.serialize();
    // console.log(description['element-0']['value']);

    $('#reaction_description').val(description['element-0']['value']);

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var serializedForms = $("#formReactionUpdate, #formTagType").serialize();

    var xhrPath = getXhrPath(
        ROUTE_REACTION_UPDATE,
        'document',
        'reactionUpdate',
        RETURN_BOOLEAN
        );
    
    return xhrCall(
        document,
        serializedForms,
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.actionSave').addClass('saved');
        }
        localLoader.hide();
    });
}

/**
 *
 */
function publishDebate(uuid)
{
    // console.log('*** publishDebate');
    // console.log(uuid);

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
    // console.log('*** publishReaction');
    // console.log(uuid);

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

/**
 *
 */
function deleteDebate(uuid)
{
    // console.log('*** deleteDebate');
    // console.log(uuid);

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
function deleteReaction(uuid)
{
    // console.log('*** deleteReaction');
    // console.log(uuid);

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
            $('#ajaxGlobalLoader').hide();
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            // redirection
            window.location = data['redirectUrl'];
        }
    });
}

