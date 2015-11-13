// notation
$("body").on("click", "[action='updateReputationUser']", function(e) {
    console.log('*** click updateReputationUser');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_USER_REPUTATION_EVOLUTION,
        'admin',
        'userReputationUpdate',
        RETURN_HTML
        );

    var subjectId = $(this).attr('subjectId');
    var evolution = $("#reputationUserEvolution").val();
    console.log('subjectId = '+subjectId);
    console.log('evolution = '+evolution);

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'subjectId': subjectId, 'evolution': evolution },
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
                $("#reputationUserScore").html(data['score']);
                $("#reputationUserEvolution").val('');
            }
        }
    });

});
