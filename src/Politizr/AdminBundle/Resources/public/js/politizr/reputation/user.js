// notation
$("body").on("click", "[action='updateReputationUser']", function(e) {
    console.log('*** click updateReputationUser');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_USER_REPUTATION_EVOLUTION,
        'user',
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
            if (data['error']) {
                $('.alert-error').html(data['error']);
                $('.alert-error').show();
            } else {
                $("#reputationUserScore").html(data['score']);
                $("#reputationUserEvolution").val('');
            }
        }
    });

});
