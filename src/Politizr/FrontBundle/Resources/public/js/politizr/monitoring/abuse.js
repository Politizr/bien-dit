// clic post abuse
$("body").on("click", "[action='createAbuseReporting']", function(e) {
    // console.log('*** click createAbuseReporting');

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ABUSE_CHECK,
        'monitoring',
        'abuseCreate',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $("#formAbuseReporting").serialize(),
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $("[action='modalClose']").trigger("click");

                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Merci, nos équipes vont étudier votre signalement rapidement.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});
