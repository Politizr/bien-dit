// follow / unfollow debate
$("body").on("click", "[action='followDebate']", function(e) {
    console.log('*** click followDebate');
    
    var context = $(this).closest('.subscribe');
    var localLoader = $(this).closest('.subscribe').find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_DEBATE,
        'document',
        'follow',
        RETURN_HTML
        );

    var subjectId = $(this).attr('subjectId');
    var way = $(this).attr('way');

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: context,
        data: { 'subjectId': subjectId, 'way': way },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // MAJ du bouton suivre / Se désabonner
                $(this).html(data['html']);

                $(this).trigger('postFollowDebateEvent', [ subjectId, way ]);
            }
        }
    });
});

// follow / unfollow user
$("body").on("click", "[action='followUser']", function(e) {
    console.log('*** click followUser');

    var context = $(this).closest('.subscribe');
    var localLoader = $(this).closest('.subscribe').find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_USER,
        'user',
        'follow',
        RETURN_HTML
        );
    
    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: context,
        data: { 'subjectId': $(this).attr('subjectId'), 'way': $(this).attr('way') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // MAJ du bouton suivre / Se désabonner
                $(this).html(data['html']);
            }
        }
    });

});
