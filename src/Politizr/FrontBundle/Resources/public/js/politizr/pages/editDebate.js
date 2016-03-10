// beta
$(function() {
    $("#formDebateUpdate").ajaxForm(options);
    stickySidebar();
});

// edit title : auto resize text area
$('.postSummaryFooter, #postText').on( 'change keyup keydown paste cut', 'textarea', function (){
    $(this).height(0).height(this.scrollHeight);
}).find( 'textarea' ).change();

/**
 * Auto save
 * Event = keyup + 2sec
 * http://stackoverflow.com/questions/9966394/can-i-delay-the-keyup-event-for-jquery
 */
var autosave = $('#debate_title, #reaction_title, .editable.description').on('keyup', delayRequest);
function dataRequest() {
    return triggerSaveDocument();
}
function delayRequest(ev) {
    console.log('*** delayRequest');
    $('.actionSave').removeClass('saved');

    if(delayRequest.timeout) {
        clearTimeout(delayRequest.timeout);
    }
    var target = this;
    delayRequest.timeout = setTimeout(function() {
        dataRequest.call(target, ev);
    }, 2000); // 2s
}

/**
 * Auto save
 * Event = mouseup
 */
$('.editable.description').on('mouseup', function() {
    console.log('mouseup debate description');
    return triggerSaveDocument();
});

// Save debate
$("body").on("click", "[action='debateSave']", function(e) {
    console.log('*** click debate save');

    return saveDebate();
});

// Save reaction
$("body").on("click", "[action='reactionSave']", function(e) {
    console.log('*** click reaction save');

    return saveDebate();
});

// Publish debate
$('body').on('click', "[action='debatePublish']", function(e){
    console.log('*** click publish debate');

    triggerSaveDocument();

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre débat. Voulez-vous publier votre débat?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return publishDebate(uuid);
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
    console.log('*** click publish reaction');

    // automatic saving before publish
    triggerSaveDocument();

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre réaction. Voulez-vous publier votre réaction?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return publishReaction(uuid);
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
    console.log('*** click delete debate');

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return debateDelete(uuid);
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
    console.log('*** click delete reaction');

    var xhrPath = getXhrPath(
        ROUTE_REACTION_DELETE,
        'document',
        'reactionDelete',
        RETURN_URL
        );

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return reactionDelete(uuid);            
        }
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

//  PHOTO

// Clic bouton upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    console.log('click file select');
    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    console.log('change file name');
    $('#formDebateUpdate').submit();
});

// Delete photo
$('body').on('click', "[action='fileDelete']", function(e){
    console.log('*** click file delete');

    $('.actionSave').removeClass('saved');

    $('#uploadedPhoto').html('');
    $('#debate_photo_info_file_name, #reaction_photo_info_file_name').val(null);

    triggerSaveDocument();    
});

