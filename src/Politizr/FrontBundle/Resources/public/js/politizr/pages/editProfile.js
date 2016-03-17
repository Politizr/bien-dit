// beta
$(function() {
    $("#formUserPhoto").ajaxForm(options);
    biographyTextCounter();
    stickySidebar();
});

// TAGS vars
var nbZones = 1;
var service = 'tag';
var xhrRoute = ROUTE_TAG_LISTING;
var xhrUrlPrefix = '/xhr';


// Update user
$("body").on("click", "[action='userProfileUpdate']", function(e) {
    console.log('*** click user profile update');

    return saveUserProfile();
});

// Update current organization
$("body").on("change", "select[action='organizationUpdate']", function(e) {
    console.log('*** change select organization');

    return saveUserOrganization();
});

// Mandate creation
$("body").on("click", "[action='mandateCreate']", function(e) {
    console.log('*** click mandateCreate');

    return createUserMandate();
});

// Mandate update
$("body").on("click", "[action='mandateUpdate']", function(e) {
    console.log('*** click mandateUpdate');

    var form = $(this).closest('#formMandateUpdate');
    var localLoader = $(this).closest('.myMandate').find('.ajaxLoader').first();
    return saveUserMandate(form, localLoader);
});

// Mandate deletion
$("body").on("click", "[action='mandateDelete']", function(e) {
    console.log('*** click mandateDelete');
    var uuid = $(this).attr('uuid');
    var localLoader = $(this).closest('.myMandate').find('.ajaxLoader').first();
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer ce mandat?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return deleteUserMandate(uuid, localLoader);
        }
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
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
    $('#formUserPhoto').submit();
});

// Delete photo
$('body').on('click', "[action='fileDelete']", function(e){
    console.log('*** click file delete');

    return deleteUserPhoto();
});


/**
 * Character counting for comment
 */
function biographyTextCounter() {
    // console.log('*** biographyTextCounter');

    $('#user_biography').textcounter({
        type                     : "character",            // "character" or "word"
        min                      : 5,                      // minimum number of characters/words
        max                      : 140,                    // maximum number of characters/words, -1 for unlimited, 'auto' to use maxlength attribute
        countContainerElement    : "div",                  // HTML element to wrap the text count in
        countContainerClass      : "bioCountWrapper",   // class applied to the countContainerElement
        textCountClass           : "textCount",           // class applied to the counter length
        inputErrorClass          : "error",                // error class appended to the input element if error occurs
        counterErrorClass        : "error",                // error class appended to the countContainerElement if error occurs
        counterText              : "Caractères: ",        // counter text
        errorTextElement         : "div",                  // error text element
        minimumErrorText         : "Minimum: 5 caractères",      // error message for minimum not met,
        maximumErrorText         : "Maximum: 140 caractères",     // error message for maximum range exceeded,
        displayErrorText         : true,                   // display error text messages for minimum/maximum values
        stopInputAtMaximum       : false,                   // stop further text input if maximum reached
        countSpaces              : true,                  // count spaces as character (only for "character" type)
        countDown                : true,                  // if the counter should deduct from maximum characters/words rather than counting up
        countDownText            : "Caractères restants: ",          // count down text
        countExtendedCharacters  : false,                       // count extended UTF-8 characters as 2 bytes (such as Chinese characters)    
    });
};

