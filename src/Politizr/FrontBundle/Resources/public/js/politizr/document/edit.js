// **************************** //
//      Auto save silencieuse
//      TODO: à affiner
//    // **************************** //
//
//    // MAJ des tags
//    $(".auto_submit_form").change(function() {
//        $('[action="debate-save"]').trigger('click', 'silence');
//    });
//
//    // Saisie de texte sur les éditeurs medium
//    // http://stackoverflow.com/questions/9966394/can-i-delay-the-keyup-event-for-jquery
//    var autosave = $('.summary-editable, .description-editable').on('keyup', delayRequest);
//    function dataRequest() {
//        // $('[action="debate-save"]').click();
//        $('[action="debate-save"]').trigger('click', 'silence');
//    }
//    function delayRequest(ev) {
//        if(delayRequest.timeout) {
//            clearTimeout(delayRequest.timeout);
//        }
//    
//        var target = this;
//    
//        delayRequest.timeout = setTimeout(function() {
//            dataRequest.call(target, ev);
//        }, 2000); // 2s
//    }
//
//    // Mise en forme sur les éditeurs medium
//    $('.summary-editable, .description-editable').on('mouseup', function() {
//        $('[action="debate-save"]').trigger('click', 'silence');
//    });
//
//
// compared mode
$("body").on("click", "[action='activeComparedEdition']", function() {
    $('#swithEditionIndependentEdition').hide();
    $('#swithEditionComparedEdition').show();
    $('#postText').animate({width: "500px"}, 300);
    $('#postText .paragraph').animate({width: "340px"}, 300);
    $('#originalText').fadeIn(800);
});

$("body").on("click", "[action='activeIndenpendentEdition']", function() {
    $('#swithEditionIndependentEdition').show();
    $('#swithEditionComparedEdition').hide();
    $('#originalText').fadeOut();
    $('#postText').animate({width: "700px"}, 300);
    $('#postText .paragraph').animate({width: "540px"}, 300);
});

// edit title : auto resize text area
$('.postSummaryFooter, #postText').on( 'change keyup keydown paste cut', 'textarea', function (){
    $(this).height(0).height(this.scrollHeight);                    
}).find( 'textarea' ).change();

// Save debate
$("body").on("click", "[action='debateSave']", function(e, mode) {
    // console.log('*** click debate save');
    // console.log(mode);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_UPDATE,
        'document',
        'debateUpdate',
        RETURN_BOOLEAN
        );
    var description = descriptionEditor.serialize();
    // console.log(description['element-0']['value']);

    $('#debate_description').val(description['element-0']['value']);

    if (mode === 'silence') {
        // sauvegarde silencieuse
        $.ajax({type: 'POST', url: xhrPath, data: $("#formDebateUpdate").serialize() });
    } else {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url : xhrPath,
            data: $("#formDebateUpdate").serialize(),
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
            statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
            success: function(data) {
                $('#ajaxGlobalLoader').hide();
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    $('#infoBoxHolder .boxSuccess .notifBoxText').html('Document bien enregistré');
                    $('#infoBoxHolder .boxSuccess').show();
                }
            }
        });
    }

    return false;
});

// Save reaction
$("body").on("click", "[action='reactionSave']", function(e, mode) {
    // console.log('*** click reaction save');
    // console.log(mode);

    var xhrPath = getXhrPath(
        ROUTE_REACTION_UPDATE,
        'document',
        'reactionUpdate',
        RETURN_BOOLEAN
        );
    var description = descriptionEditor.serialize();
    // console.log(description['element-0']['value']);

    $('#reaction_description').val(description['element-0']['value']);
 
    if (mode === 'silence') {
        // sauvegarde silencieuse
        $.ajax({type: 'POST', url: xhrPath, data: $("#formReactionUpdate").serialize() });
    } else {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url : xhrPath,
            data: $("#formReactionUpdate").serialize(),
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
            statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
            success: function(data) {
                $('#ajaxGlobalLoader').hide();
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    $('#infoBoxHolder .boxSuccess .notifBoxText').html('Document bien enregistré');
                    $('#infoBoxHolder .boxSuccess').show();
                }
            }
        });
    }

    return false;
});

// Publish debate
$('body').on('click', "[action='debatePublish']", function(e){
    // console.log('*** click publish debate');

    // automatic saving before publish
    $('[action="debateSave"]').trigger('click', 'silence');

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_PUBLISH,
        'document',
        'debatePublish',
        RETURN_URL
        );

    var subjectId = $(this).attr('subjectId');
    var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre débat. Voulez-vous publier votre débat?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url : xhrPath,
                data: { 'id': subjectId },
                dataType: 'json',
                beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
                statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
                error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
                success: function(data) {
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
    }, {
        ok: "Publier",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

// Publish reaction
$('body').on('click', "[action='reactionPublish']", function(e){
    // console.log('*** click publish reaction');

    // automatic saving before publish
    $('[action="reactionSave"]').trigger('click', 'silence');

    var xhrPath = getXhrPath(
        ROUTE_REACTION_PUBLISH,
        'document',
        'reactionPublish',
        RETURN_URL
        );
    
    var subjectId = $(this).attr('subjectId');
    var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre réaction. Voulez-vous publier votre réaction?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url : xhrPath,
                data: { 'id': subjectId },
                dataType: 'json',
                beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
                statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
                error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
                success: function(data) {
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
    }, {
        ok: "Publier",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

// Delete debate
$('body').on('click', "[action='debateDelete']", function(e){
    // console.log('*** click delete debate');

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_DELETE,
        'document',
        'debateDelete',
        RETURN_URL
        );

    var subjectId = $('#debate_id').val();
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url : xhrPath,
                data: { 'id': subjectId },
                dataType: 'json',
                beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
                statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
                error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
                success: function(data) {
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
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

// Delete reaction
$('body').on('click', "[action='reactionDelete']", function(e){
    // console.log('*** click delete reaction');

    var xhrPath = getXhrPath(
        ROUTE_REACTION_DELETE,
        'document',
        'reactionDelete',
        RETURN_URL
        );

    var subjectId = $('#reaction_id').val();
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url : xhrPath,
                data: { 'id': subjectId },
                dataType: 'json',
                beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
                statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
                error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
                success: function(data) {
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
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});
