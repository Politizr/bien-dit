// clic post abuse
$("body").on("click", "[action='createAskForUpdate']", function(e) {
    // console.log('*** click createAskForUpdate');

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK,
        'monitoring',
        'askForUpdateCreate',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $("#formAskForUpdate").serialize(),
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
                $("[action='modalClose']").trigger("click");

                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Merci, nos équipes vont étudier votre demande de modification rapidement.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});
