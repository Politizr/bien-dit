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

$('#debate_title, #reaction_title, .editable.description').on('keyup', delayRequest);

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
 * Remove "marked as save" and/or auto save
 */

// dlblick editor event
$('.editable.description').on('dblclick', function() {
    // console.log('dblclick event');
    $('.actionSave').removeClass('saved');
    // delayRequest(this);
});

// mouseup editor event
$('.editable.description').on('mouseup', function() {
    // console.log('mouseup event');
    $('.actionSave').removeClass('saved');
    // delayRequest(this);
});

// // change checkbox type event
// $('#formTagType :checkbox').on('change', function() {
//     // console.log('checkbox change');
//     delayRequest(this);
// });


function triggerSaveDocument()
{
    // console.log('*** triggerSaveDocument');

    var documentSave = $('.actionSave').find('a').attr('action');
    return $('[action="'+documentSave+'"]').trigger('click');
}


/**
 *
 */
function saveDocumentAttr(uuid, type)
{
    // console.log('*** saveDocumentAttr');
    // console.log(uuid);
    // console.log(type);

    saveDocumentAttrDocLoc(uuid, type);
    saveDocumentAttrTagType(uuid, type);
    saveDocumentAttrTagFamily(uuid, type);
}

/**
 *
 */
function saveDocumentAttrDocLoc(uuid, type)
{
    // console.log('*** saveDocumentAttrDocLoc');
    // console.log(uuid);
    // console.log(type);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_UPDATE,
        'document',
        'documentAttrUpdateDocLoc',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        $("#formDocLoc").serialize() + '&uuid=' + uuid + '&type=' + type,
        xhrPath,
        null,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        }
    });
}

/**
 *
 */
function saveDocumentAttrTagType(uuid, type)
{
    // console.log('*** saveDocumentAttrTagType');
    // console.log(uuid);
    // console.log(type);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_UPDATE,
        'document',
        'documentAttrUpdateTagType',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        $("#formTagType").serialize() + '&uuid=' + uuid + '&type=' + type,
        xhrPath,
        null,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        }
    });
}

/**
 *
 */
function saveDocumentAttrTagFamily(uuid, type)
{
    // console.log('*** saveDocumentAttrTagFamily');
    // console.log(uuid);
    // console.log(type);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_UPDATE,
        'document',
        'documentAttrUpdateTagFamily',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        $("#formTagFamily").serialize() + '&uuid=' + uuid + '&type=' + type,
        xhrPath,
        null,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        }
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
function saveDebate()
{
    // console.log('*** saveDebate');

    var description = descriptionEditor.serialize();
    // console.log(description['element-0']['value']);

    $('#debate_description').val(description['element-0']['value']);

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var serializedForms = $("#formDebateUpdate, #formTagType, #formTagFamily").serialize();

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
        localLoader,
        'POST'
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

    var serializedForms = $("#formReactionUpdate, #formTagType, #formTagFamily").serialize();

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
        localLoader,
        'POST'
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
        1,
        'POST'
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
        1,
        'POST'
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
 * Update debate's tag zone
 *
 * @param string uuid
 */
function updateDebateTagsZone(uuid)
{
    // console.log('*** updateDebateTagsZone');
    // console.log(uuid);

    var localLoader = $('.tagList').find('.ajaxLoader').first();
    var targetElement = $('.tagList');

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_DOC_TAGS,
        'document',
        'updateDebateTagsZone',
        RETURN_HTML
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#ajaxGlobalLoader').hide();
        } else {
            targetElement.html(data['html']);            
        }
    });    
}

/**
 * Update reaction's tag zone
 *
 * @param string uuid
 */
function updateReactionTagsZone(uuid)
{
    // console.log('*** updateReactionTagsZone');
    // console.log(uuid);

    var localLoader = $('.tagList').find('.ajaxLoader').first();
    var targetElement = $('.tagList');

    var xhrPath = getXhrPath(
        ROUTE_REACTION_DOC_TAGS,
        'document',
        'updateReactionTagsZone',
        RETURN_HTML
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#ajaxGlobalLoader').hide();
        } else {
            targetElement.html(data['html']);            
        }
    });    
}

/**
 * Show / hide div attributes relative to zone choice
 */
function locShowHideAttr() {
    // console.log('*** locShowHideAttr');

    if ($('#document_localization_loc_type_0').is(':checked')) {
        // console.log('document_localization_loc_type_0 :checked');
        $('#document_localization_localization_city').show();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_1').is(':checked')) {
        // console.log('document_localization_loc_type_1 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').show();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_2').is(':checked')) {
        // console.log('document_localization_loc_type_2 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').show();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_3').is(':checked')) {
        // console.log('document_localization_loc_type_3 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_4').is(':checked')) {
        // console.log('document_localization_loc_type_4 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').show();
    }
}
