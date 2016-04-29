// beta

/**
 * Reinit password
 */
function lostPasswordCheck(form) {
    console.log('*** lostPasswordCheck');
    console.log(form);

    var xhrPath = getXhrPath(
        ROUTE_SECURITY_LOST_PASSWORD_CHECK,
        'security',
        'lostPasswordCheck',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            form.trigger("reset");
            
            // notif message
            $("#infoBoxHolder .boxSuccess .notifBoxText").html('Un nouveau mot de passe vient de vous être envoyé par email.');
            $('#infoBoxHolder .boxSuccess').show();
        }
        $('#ajaxGlobalLoader').hide();
    });
}
