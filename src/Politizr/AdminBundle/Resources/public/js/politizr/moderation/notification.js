// notation
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
