$(function() {
    // photo upload management
    $("#formDebateUpdate").ajaxForm(options);

    // modal attributes show/hide
    $('.modalPublish').hide();

    // modal city/dep/region/country selection show/hide
    locShowHideAttr();

    // sticky sidebar
    stickySidebar();
});

// TAG vars
var nbZones = 1;
var service = 'tag';
var xhrRoute = ROUTE_TAG_LISTING;
var xhrUrlPrefix = '/xhr';

// Modal show / hide
$('body').on('click', "[action='openModalPublish']", function(e){
    // console.log('*** click open modal publish');

    $.when(triggerSaveDocument()).done(function(r1) {
        $('body').addClass('noScroll');
        $('.modalPublish').show();
    });
});

$('body').on('click', "[action='closeModalPublish'], .modalPublishBg", function(e){
    // console.log('*** click close modal publish');

    var uuid = $('input[name="uuid"]').val();
    // console.log(uuid);
    
    updateDebateTagsZone(uuid);

    $('body').removeClass('noScroll');
    $('.modalPublish').hide();
});

// Modal doc loc management
// change checkbox type event
$('#formDocLoc :radio').on('change', function() {
    // console.log('*** formDocLoc change');
    locShowHideAttr();
    saveDocumentAttr();
});

// change checkbox type event
$('#formTagType :checkbox, #formTagFamily :checkbox').on('change', function() {
    // console.log('*** formTagType change');
    saveDocumentAttr();
});


// Publish debate from attr > final publication
$('body').on('click', "[action='debatePublish']", function(e){
    // console.log('*** click publish debate');
    var uuid = $(this).attr('uuid');

    $.when(saveDocumentAttr()).done(function(r1) {
        return publishDebate(uuid);
        // var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre sujet de discussion. Voulez-vous publier votre sujet?";
        // smoke.confirm(confirmMsg, function(e) {
        //     if (e) {
        //         return publishDebate(uuid);
        //     }
        // }, {
        //     ok: "Publier",
        //     cancel: "Annuler"
        //     // classname: "custom-class",
        //     // reverseButtons: true
        // });
    });
});


// Save debate
$("body").on("click", "[action='debateSave']", function(e) {
    // console.log('*** click debate save');

    return saveDebate();
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

    var uuid = $(this).attr('uuid');
    var type = $(this).attr('type');

    return deleteDocumentPhoto(uuid, type);
});

