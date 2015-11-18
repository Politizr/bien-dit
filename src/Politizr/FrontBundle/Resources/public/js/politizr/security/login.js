// show / hide connection / lost password boxes
$("body").on("click", "[action='showLostPasswordBox']", function(e) {
    // console.log('*** click showLostPasswordBox');

    $('#lostPasswordForm').show();
    $('#loginForm').hide();
});

$("body").on("click", "[action='showLoginBox']", function(e) {
    // console.log('*** click showLoginBox');

    $('#lostPasswordForm').hide();
    $('#loginForm').show();
});

/**
 * Connexion
 */
$("body").on("click", "[action='login']", function(e) {
    console.log('*** click login');
    $('#ajaxGlobalLoader').show();
    $("#formLogin").submit();
});

/**
 * Lost password
 */
$("body").on("click", "[action='reinitPassword']", function(e) {
    // console.log('*** click reinitPassword');

    var xhrPath = getXhrPath(
        ROUTE_SECURITY_LOST_PASSWORD_CHECK,
        'security',
        'lostPasswordCheck',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $("#formLostPassword").serialize(),
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // close modal
                $("[action='modalClose']").trigger('click');

                // notif message
                $("#infoBoxHolder .boxSuccess .notifBoxText").html('Un nouveau mot de passe vient de vous être envoyé par email.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});

