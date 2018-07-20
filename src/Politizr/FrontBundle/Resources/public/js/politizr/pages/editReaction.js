// GLOBAL vars
var uuid = $('#formReactionUpdate').attr('uuid');
var type = $('#formReactionUpdate').attr('type');

// beta
$(function() {
    // modal attributes show/hide
    $('.modalPublish').hide();

    // modal city/dep/region/country selection show/hide
    locShowHideAttr();
    updateReactionTagsZone(uuid);

    // showTab / default "mode comparé"
    $("[action='showTab']:last-of-type").trigger('click');

    // sticky sidebar
    stickySidebar();
});

// TAG vars
var nbZones = 1;
var service = 'tag';
var xhrRoute = ROUTE_TAG_LISTING;
var xhrUrlPrefix = '/xhr';


// 2 tabs in sidebar
$("body").on("click", "[action='showTab']:first-of-type", function() {
    $(this).parents().siblings('.sidebarTabs').hide();
    $(this).parents().siblings('.sidebarTabs.activeTab').show();
    $('.sidebarTabsHeader span').removeClass('activeTabHeader');
    $(this).addClass('activeTabHeader');
});

$("body").on("click", "[action='showTab']:last-of-type", function() {
    $(this).parents().siblings('.sidebarTabs').show();
    $(this).parents().siblings('.sidebarTabs.activeTab').hide();
    $('.sidebarTabsHeader span').removeClass('activeTabHeader');
    $(this).addClass('activeTabHeader');
});

// Modal show / hide
$('body').on('click', "[action='openModalPublish']", function(e){
    console.log('*** click open modal publish');

    $.when(triggerSaveDocument()).done(function(r1) {
        $('.modalPublish').show();
    });
});

$('body').on('click', "[action='closeModalPublish']", function(e){
    console.log('*** click close modal publish');

    updateReactionTagsZone(uuid);

    $('body').removeClass('noScroll');
    $('.modalPublish').hide();
});

// Modal doc loc management
// change checkbox type event
$('#formDocLoc :radio').on('change', function() {
    console.log('*** formDocLoc change');
    locShowHideAttr();
    saveDocumentAttr(uuid, type);
});

// change checkbox type event
$('#formTagType :checkbox, #formTagFamily :checkbox').on('change', function() {
    console.log('*** formTagType change');
    saveDocumentAttr(uuid, type);
});


// Save reaction
$("body").on("click", "[action='reactionSave']", function(e) {
    console.log('*** click reaction save');

    return saveReaction();
});

// Delete reaction
$('body').on('click', "[action='reactionDelete']", function(e){
    console.log('*** click delete reaction');

    var confirmMsg = "Êtes-vous sûr de vouloir supprimer?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return deleteReaction(uuid);            
        }
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

// Publish reaction from attr > final publication
$('body').on('click', "[action='reactionPublish']", function(e){
    console.log('*** click publish reaction');

    $.when(saveDocumentAttr(uuid, type)).done(function(r1) {
        return publishReaction(uuid);
    });
});
