// beta
$(function() {
    // photo upload management
    $("#formReactionUpdate").ajaxForm(options);

    // modal attributes show/hide
    $('.modalPublish').hide();

    // modal city/dep/region/country selection show/hide
    locShowHideAttr();

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
    // console.log('*** click open modal publish');

    $.when(triggerSaveDocument()).done(function(r1) {
        $('.modalPublish').show();
    });
});

$('body').on('click', "[action='closeModalPublish']", function(e){
    // console.log('*** click close modal publish');

    var uuid = $('input[name="uuid"]').val();
    // console.log(uuid);
    
    updateReactionTagsZone(uuid);

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


// Publish reaction from attr > final publication
$('body').on('click', "[action='reactionPublish']", function(e){
    // console.log('*** click publish reaction');
    var uuid = $(this).attr('uuid');

    $.when(saveDocumentAttr()).done(function(r1) {
        return publishReaction(uuid);
        // var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre réponse. Voulez-vous publier votre réponse?";
        // smoke.confirm(confirmMsg, function(e) {
        //     if (e) {
        //         return publishReaction(uuid);
        //     }
        // }, {
        //     ok: "Publier",
        //     cancel: "Annuler"
        //     // classname: "custom-class",
        //     // reverseButtons: true
        // });
    });
});


// Save reaction
$("body").on("click", "[action='reactionSave']", function(e) {
    // console.log('*** click reaction save');

    return saveReaction();
});

// Delete reaction
$('body').on('click', "[action='reactionDelete']", function(e){
    // console.log('*** click delete reaction');

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
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
    $('#formReactionUpdate').submit();
});

// Delete photo
$('body').on('click', "[action='fileDelete']", function(e){
    // console.log('*** click file delete');

    var uuid = $(this).attr('uuid');
    var type = $(this).attr('type');

    return deleteDocumentPhoto(uuid, type);
});

