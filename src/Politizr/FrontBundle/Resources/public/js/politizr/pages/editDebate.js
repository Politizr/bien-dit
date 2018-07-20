// GLOBAL vars
var uuid = $('#formDebateUpdate').attr('uuid');
var type = $('#formDebateUpdate').attr('type');

$(function() {
    // modal attributes show/hide
    $('.modalPublish').hide();

    // modal city/dep/region/country selection show/hide
    locShowHideAttr();
    updateDebateTagsZone(uuid);

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

    updateDebateTagsZone(uuid);

    $('body').removeClass('noScroll');
    $('.modalPublish').hide();
});

// Modal doc loc management
// change checkbox type event
$('#formDocLoc :radio').on('change', function() {
    // console.log('*** formDocLoc change');
    locShowHideAttr();
    saveDocumentAttr(uuid, type);
});

// change checkbox type event
$('#formTagType :checkbox, #formTagFamily :checkbox').on('change', function() {
    // console.log('*** formTagType change');
    saveDocumentAttr(uuid, type);
});

// Save debate
$("body").on("click", "[action='debateSave']", function(e) {
    // console.log('*** click debate save');

    return saveDebate();
});

// Delete debate
$('body').on('click', "[action='debateDelete']", function(e){
    // console.log('*** click delete debate');

    var confirmMsg = "Êtes-vous sûr de vouloir supprimer?";
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

// Publish debate from attr > final publication
$('body').on('click', "[action='debatePublish']", function(e){
    // console.log('*** click publish debate');

    $.when(saveDocumentAttr(uuid, type)).done(function(r1) {
        return publishDebate(uuid);
    });
});
