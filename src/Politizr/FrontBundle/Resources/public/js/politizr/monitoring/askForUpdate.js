// beta

// Ask for update
$("body").on("click", "[action='askForUpdate']", function() {
    // console.log('*** askForUpdate');

    context = $(this).closest(".askForUpdateBox");

    if ($('.formAskForUpdateContent').is(":visible")) {
        $("[action='closeAskForUpdate']").trigger("click");
    } else {
        var uuid = $(this).attr('uuid');
        var type = $(this).attr('type');
        var localLoader = context.find('.ajaxLoader').first();
        context.find('.askForUpdateForm').slideToggle(400, 'swing', loadFormAskForUpdate(context, localLoader, uuid, type));
    }
}); 

$("body").on("click", "[action='closeAskForUpdate']", function() {
    // console.log('*** closeAskForUpdate');

    $('.askForUpdateForm').hide();
    $('.formAskForUpdateContent').html('');
});

$("body").on("click", "[action='createAskForUpdate']", function() {
    // console.log('*** createAskForUpdate');

    var context = $(this).closest(".askForUpdateBox");
    var localLoader = context.find('.ajaxLoader').first();

    $.when(
        validateFormAskForUpdate(context, localLoader)
    ).done(function(r1) {
        if (!r1['error']) {
            $("[action='closeAskForUpdate']").trigger("click");
        }
    });
});


/**
 * Form ask for update box loading
 *
 * @param context
 * @param localLoader
 * @param uuid
 * @param type
 */
function loadFormAskForUpdate(context, localLoader, uuid, type) {
    // console.log('*** loadFormAskForUpdate');
    uuid = (typeof uuid === "undefined") ? null : uuid;
    type = (typeof type === "undefined") ? null : type;

    if (uuid == null || type == null) {
        return false;
    }

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ASK_FOR_UPDATE,
        'monitoring',
        'askForUpdate',
        RETURN_HTML
        );

    return xhrCall(
        context,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            context.find('.formAskForUpdateContent').html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * Form ask for update validation
 *
 * @param context
 * @param localLoader
 */
function validateFormAskForUpdate(context, localLoader) {
    // console.log('*** validateFormAskForUpdate');

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK,
        'monitoring',
        'askForUpdateCreate',
        RETURN_BOOLEAN
        );

    var form = context.find("#formAskForUpdate");
    // console.log(form.serialize());

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Merci, nos équipes vont étudier votre demande de modification rapidement.');
            $('#infoBoxHolder .boxSuccess').show();
        }
        localLoader.hide();
    });
}
