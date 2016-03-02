// Abuse reporting
$("body").on("click", "[action='askForUpdate']", function() {
    if ($('#formAskForUpdate').is(":visible")) {
        $('.sidebarAskForModificationForm').slideToggle();
        $('#formAskForUpdate').html('');
    } else {
        var uuid = $(this).attr('uuid');
        var type = $(this).attr('type');
        var localLoader = $('.sidebarAskForModificationForm').find('.ajaxLoader').first();
        $('.sidebarAskForModificationForm').slideToggle(400, 'swing', loadFormAskForUpdate(uuid, type, localLoader));
    }
}); 

$("body").on("click", "[action='closeAskForUpdate']", function() {
    $('.sidebarAskForModificationForm').slideToggle();
    $('#formAskForUpdate').html('');
});

$("body").on("click", "[action='createAskForUpdate']", function() {
    var localLoader = $('.sidebarAskForModificationForm').find('.ajaxLoader').first();

    $.when(
        validateFormAskForUpdate(localLoader)
    ).done(function(r1) {
        if (!r1['error']) {
            $('.sidebarAskForModificationForm').slideToggle();
            $('#formAskForUpdate').html('');
        }
    });
});


/**
 * Form abuse box loading
 */
function loadFormAskForUpdate(uuid, type, localLoader) {
    console.log('*** loadFormAskForUpdate');
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
        document,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#formAskForUpdate').html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * Form abuse box loading
 */
function validateFormAskForUpdate(localLoader) {
    console.log('*** validateFormAskForUpdate');

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK,
        'monitoring',
        'askForUpdateCreate',
        RETURN_BOOLEAN
        );

    return xhrCall(
        document,
        $("#formAskForUpdate").serialize(),
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
