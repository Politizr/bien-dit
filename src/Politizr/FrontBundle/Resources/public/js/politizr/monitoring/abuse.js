// beta

// Abuse reporting
$("body").on("click", "[action='abuseReporting']", function() {
    // console.log('*** abuseReporting');

    context = $(this).closest(".reportBox");
    if ($('.formAbuseContent').is(":visible")) {
        $("[action='closeAbuseReporting']").trigger("click");
    } else {
        var uuid = $(this).attr('uuid');
        var type = $(this).attr('type');
        var localLoader = context.find('.ajaxLoader').first();
        context.find('.reportForm').slideToggle(400, 'swing', loadFormAbuse(context, localLoader, uuid, type));
    }
}); 

$("body").on("click", "[action='closeAbuseReporting']", function() {
    // console.log('*** closeAbuseReporting');

    $('.reportForm').hide();
    $('.formAbuseContent').html('');
});

$("body").on("click", "[action='createAbuseReporting']", function() {
    // console.log('*** createAbuseReporting');

    var context = $(this).closest(".reportBox");
    var localLoader = context.find('.ajaxLoader').first();

    $.when(
        validateFormAbuse(context, localLoader)
    ).done(function(r1) {
        if (!r1['error']) {
            $("[action='closeAbuseReporting']").trigger("click");
        }
    });
});


/**
 * Form abuse box loading
 *
 * @param context
 * @param localLoader
 * @param uuid
 * @param type
 */
function loadFormAbuse(context, localLoader, uuid, type) {
    // console.log('*** loadFormAbuse');
    uuid = (typeof uuid === "undefined") ? null : uuid;
    type = (typeof type === "undefined") ? null : type;

    if (uuid == null || type == null) {
        return false;
    }

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ABUSE,
        'monitoring',
        'abuse',
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
            context.find('.formAbuseContent').html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * Form abuse box validation
 *
 * @param context
 * @param localLoader
 */
function validateFormAbuse(context, localLoader) {
    // console.log('*** validateFormAbuse');

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ABUSE_CHECK,
        'monitoring',
        'abuseCreate',
        RETURN_BOOLEAN
        );

    var form = context.find("#formAbuseReporting");
    // console.log(form.serialize());

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
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Merci, nos équipes vont étudier votre signalement rapidement.');
            $('#infoBoxHolder .boxSuccess').show();
        }
        localLoader.hide();
    });
}
