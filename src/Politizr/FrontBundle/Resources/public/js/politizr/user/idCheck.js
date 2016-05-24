// beta

// Options Jquery Form JS > upload ajax
var idCheckPhotoOptions = {
    // iframe: true,
    beforeSend: function() 
    {
        $('#ajaxGlobalLoader').show();
    },
    success: function(responseText, statusText, xhr, form)
    {
        data = $.parseJSON( responseText );
        console.log(data);

        if ( 'success' == statusText && data['success']) {
            validateIdPhoto(data['fileName']);
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        }
    },
    error: function(data)
    {
        $('#ajaxGlobalLoader').hide();
        $('#infoBoxHolder .boxError .notifBoxText').html('Erreur inconnue: merci de recharger la page.');
        $('#infoBoxHolder .boxError').show();
    }
};

$(function() {
    $("#formIdCheckPhoto").ajaxForm(idCheckPhotoOptions);
    zlaTextCounter();
});

// Clic bouton upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    console.log('click file select');

    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    console.log('change file name');

    $('#formIdCheckPhoto').submit();
});

// id check validate ZLA
$("body").on("click", "button[action='validateIdZla']", function(e) {
    console.log('click submitPerso');

    var form = $(this).closest('form');

    return validateIdZla(form);
});

/**
 * Id check / validate ZLA
 * @param form
 */
function validateIdZla(form)
{
    console.log('click validateIdZla');
    console.log(form);

    var xhrPath = getXhrPath(
        ROUTE_USER_VALIDATE_ID,
        'security',
        'validateIdZla',
        RETURN_HTML
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
            $('#ajaxGlobalLoader').hide();
        } else {
            console.log(data);
            if (data['success']) {
                window.location = data['redirectUrl'];
            } else if (data['redirect']) {
                window.location = data['redirectUrl'];
            } else {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['errors']);
                $('#infoBoxHolder .boxError').show();

                console.log(data['nbTryLeft'])
                $('#nbTryLeft').html(data['nbTryLeft']);

                if (data['nbTryLeft'] == 0) {
                    $('#idCheck').hide();
                }

                $('#ajaxGlobalLoader').hide();
            }
        }
    });
}

/**
 * Id check / validate photo
 * @param
 */
function validateIdPhoto(fileName)
{
    console.log('click validateIdPhoto');
    console.log(fileName);

    var xhrPath = getXhrPath(
        ROUTE_USER_VALIDATE_PHOTO_UPLOAD,
        'security',
        'validateIdPhoto',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'fileName': fileName },
        xhrPath,
        1
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
            $('#ajaxGlobalLoader').hide();
        } else {
            console.log(data);
            if (data['success']) {
                window.location = data['redirectUrl'];
            } else if (data['redirect']) {
                window.location = data['redirectUrl'];
            } else {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['errors']);
                $('#infoBoxHolder .boxError').show();

                console.log(data['nbTryLeft'])
                $('#nbTryLeft').html(data['nbTryLeft']);

                if (data['nbTryLeft'] == 0) {
                    $('#idCheck').hide();
                }

                $('#ajaxGlobalLoader').hide();
            }
        }
    });
}

/**
 * Character counting for zla
 */
function zlaTextCounter() {
    console.log('*** zlaTextCounter');

    $('.zlaInputs input').textcounter({
        type                     : "character",            // "character" or "word"
        min                      : 36,                      // minimum number of characters/words
        max                      : 36,                    // maximum number of characters/words, -1 for unlimited, 'auto' to use maxlength attribute
        countContainerElement    : "div",                  // HTML element to wrap the text count in
        countContainerClass      : "zlaCountWrapper",   // class applied to the countContainerElement
        textCountClass           : "textCount",           // class applied to the counter length
        inputErrorClass          : "error",                // error class appended to the input element if error occurs
        counterErrorClass        : "error",                // error class appended to the countContainerElement if error occurs
        counterText              : "Caractères: ",        // counter text
        errorTextElement         : "div",                  // error text element
        minimumErrorText         : "",      // error message for minimum not met,
        maximumErrorText         : "",     // error message for maximum range exceeded,
        displayErrorText         : true,                   // display error text messages for minimum/maximum values
        stopInputAtMaximum       : true,                   // stop further text input if maximum reached
        countSpaces              : true,                  // count spaces as character (only for "character" type)
        countDown                : true,                  // if the counter should deduct from maximum characters/words rather than counting up
        countDownText            : "",                      // count down text
        countExtendedCharacters  : true,                       // count extended UTF-8 characters as 2 bytes (such as Chinese characters)    
    });
};

