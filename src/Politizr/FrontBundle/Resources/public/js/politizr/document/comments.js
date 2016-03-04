// beta
// on document ready
$(function() {
    if(window.location.hash) {
        var paragraphId = window.location.hash.substr(3);
        // console.log(paragraphId);

        if (paragraphId > 0) {
            var clickContext = $('#p-'+paragraphId).find("[action='comments']");
            // console.log(clickContext);
            clickContext.trigger("click");
        } else {
            $("[action='globalComments']").trigger("click");
        }
    }
});

// open paragraph comments
$("body").on("click", "[action='comments']", function() {
    // console.log('*** click comments');

    context = $(this).closest('.paragraphHolder');

    if (context.find('.commentsContent').is(':visible')) {
        $("[action='closeComments']").trigger("click");
    } else {
        $('.bubblesComments').hide();
        $('.commentsCounter').removeClass('activeComment');

        context.find('.bubblesComments').toggle();

        return loadParagraphContent(context);
    }
});

$("body").on("click", "[action='globalComments']", function() {
    // console.log('*** click globalComments');
    
    context = $(this).closest('.paragraphHolder');
    if (context.find('.commentsContent').is(':visible')) {
        // @todo this call is not working, why?
        // $("[action='closeComments']").trigger("click");
        context.find('#globalComments').hide();
        context.find('#globalComments').html('');
    } else {
        $('.bubblesComments').hide();
        $('.commentsCounter').removeClass('activeComment');

        context.find('#globalComments').toggle();

        return loadParagraphContent(context);
    }
});

// close comments
$("body").on("click", "[action='closeComments']", function() {
    // console.log('*** click closeComments');

    context = $(this).closest('.paragraphHolder');

    $('.bubblesComments').hide();
    $('#globalComments').hide();

    $('.commentsCounter').removeClass('activeComment');

    context.find('.commentsContent').html('');
    context.find('#globalComments').html('');
});

// ajustement de la taille du textarea de saisie d'un commentaire en fonction de la saisie en cours
$("body").on('change keyup keydown paste cut', 'textarea', function () {
    $(this).height(0).height(this.scrollHeight);                    
}).find('textarea').change(); 


// création d'un commentaire
$("body").on("click", "input[action='createComment']", function(e) {
    // console.log('*** click createComment');
    
    var context = $(this).closest('.paragraphHolder');
    createComment(context);
});

/**
 * Load paragraph id comments
 *
 * @param div context
 */
function loadParagraphContent(context)
{
    // console.log('*** loadParagraphContent');
    // console.log(context);

    var uuid = context.attr('uuid');
    var type = context.attr('type');
    var noParagraph = context.attr('noParagraph');

    // console.log(uuid);
    // console.log(type);
    // console.log(noParagraph);

    var localLoader = context.find('.ajaxLoader').first();
    // console.log(localLoader);
    var targetElement = context.find('.commentsContent').first();
    // console.log(targetElement);
    var targetCounter = context.find('.counterContent').first();
    // console.log(targetCounter);

    var xhrPath = getXhrPath(
        ROUTE_COMMENTS,
        'document',
        'comments',
        RETURN_HTML
        );

    return xhrCall(
        context,
        { 'uuid': uuid, 'type': type, 'noParagraph': noParagraph },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
            targetCounter.html(data['counter']);
            fullImgLiquid();
            commentTextCounter();
            $('#comment_description').focus();
        }
        localLoader.hide();
    });
}

/**
 *
 */
function createComment(context)
{
    // console.log('*** createComment');
    // console.log(context);

    var localLoader = context.find('.formCommentNew').find('.ajaxLoader').first();
    // console.log(localLoader);
    var targetElement = context.find('.commentsContent').first();
    // console.log(targetElement);

    var textCount = $('.textCount').text();
    // console.log(textCount);

    if (textCount > 495 || textCount < 0) {
        return false;
    }

    var form = context.find(".formCommentNew").first();
    // console.log(form);

    var xhrPath = getXhrPath(
        ROUTE_COMMENT_CREATE,
        'document',
        'commentNew',
        RETURN_BOOLEAN
        );

    return xhrCall(
        context,
        form.serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
           return loadParagraphContent(context);
        }
        localLoader.hide();
    });
}


/**
 * Character counting for comment
 */
function commentTextCounter() {
    // // console.log('*** commentTextCounter');

    $('#comment_description').textcounter({
        type                     : "character",            // "character" or "word"
        min                      : 5,                      // minimum number of characters/words
        max                      : 500,                    // maximum number of characters/words, -1 for unlimited, 'auto' to use maxlength attribute
        countContainerElement    : "div",                  // HTML element to wrap the text count in
        countContainerClass      : "commentCountWrapper",   // class applied to the countContainerElement
        textCountClass           : "textCount",           // class applied to the counter length
        inputErrorClass          : "error",                // error class appended to the input element if error occurs
        counterErrorClass        : "error",                // error class appended to the countContainerElement if error occurs
        counterText              : "Caractères: ",        // counter text
        errorTextElement         : "div",                  // error text element
        minimumErrorText         : "Minimum: 5 caractères",      // error message for minimum not met,
        maximumErrorText         : "Maximum: 500 caractères",     // error message for maximum range exceeded,
        displayErrorText         : true,                   // display error text messages for minimum/maximum values
        stopInputAtMaximum       : false,                   // stop further text input if maximum reached
        countSpaces              : true,                  // count spaces as character (only for "character" type)
        countDown                : true,                  // if the counter should deduct from maximum characters/words rather than counting up
        countDownText            : "Caractères restants: ",          // count down text
        countExtendedCharacters  : false,                       // count extended UTF-8 characters as 2 bytes (such as Chinese characters)    
    });
};

