/**
 * moderation notification
 */
$("body").on("click", "[action='moderationAlertNew']", function(e) {
    console.log('*** click moderationAlertNew');

    if (!confirm('Êtes-vous sûr?')) {
        return false;
    }

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_USER_MODERATION_ALERT_NEW,
        'admin',
        'userModeratedNew',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $("#moderationAlertNew").serialize(),
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown ); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $("#moderationListing").html(data['listing']);
                $("#moderationAlertNew").trigger("reset");
            }
        }
    });

});

/**
 * moderation banned email
 */
$("body").on("click", "[action='bannedEmail']", function(e) {
    console.log('*** click bannedEmail');

    if (!confirm('Êtes-vous sûr?')) {
        return false;
    }

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_USER_MODERATION_BANNED_EMAIL,
        'admin',
        'bannedEmail',
        RETURN_BOOLEAN
        );

    var subjectId = $(this).attr('subjectId');

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'subjectId': subjectId },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown ); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Email envoyé.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });

});


/**
 * admin notification
 */
function createAdminNotification()
{
    console.log('*** createAdminNotification');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_NOTIF_CREATE,
        'dashboard',
        'adminNotif',
        RETURN_HTML
    );

    return xhrCall(
        document,
        $("#formNotification").serialize(),
        xhrPath
    ).done(function(data) {
        $('#ajaxGlobalLoader').hide();
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Notif envoyée.');
            $('#infoBoxHolder .boxSuccess').show();

            // reset form
            $('#formNotification')[0].reset();

            // upd last notif
            $('#notificationsAdminHistory').html(data['notifLast']);
        }
    });
}

