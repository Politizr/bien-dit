// beta
$(function() {
    $("#formDebateUpdate").ajaxForm(options);
    stickySidebar();
});

// Save debate
$("body").on("click", "[action='debateSave']", function(e) {
    // console.log('*** click debate save');

    return saveDebate();
});

// Publish debate
$('body').on('click', "[action='debatePublish']", function(e){
    // console.log('*** click publish debate');

    $.when(triggerSaveDocument()).done(function(r1) {
        var uuid = $(this).attr('uuid');
        var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre sujet de discussion. Voulez-vous publier votre sujet?";
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
});

// Delete debate
$('body').on('click', "[action='debateDelete']", function(e){
    // console.log('*** click delete debate');

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return deleteDebate(uuid);
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
    // console.log('click file select');
    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    // console.log('change file name');
    $('#formDebateUpdate').submit();
});

// Delete photo
$('body').on('click', "[action='fileDelete']", function(e){
    // console.log('*** click file delete');

    $('.actionSave').removeClass('saved');

    $('#uploadedPhoto').html('');
    $('#debate_photo_info_file_name').val(null);

    triggerSaveDocument();    
});

