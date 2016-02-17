// follow / unfollow debate
$("body").on("click", "[action='followDebate']", function(e) {
    // console.log('*** click followDebate');
    
    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_DEBATE,
        'document',
        'follow',
        RETURN_HTML
        );

    var context = $(this).closest('.actionFollow');
    var localLoader = $(this).closest('.actionFollow').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = $(this).attr('way');
    // console.log('uuid = '+uuid);
    // console.log('way = '+way);

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: context,
        data: { 'uuid': uuid, 'way': way },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // update follow / unfollow
                $(this).html(data['html']);

                // @todo to plug w. new listing
                // $(this).trigger('postFollowDebateEvent', [ way ]);

                // update reputation counter
                scoreCounter();
                badgesCounter();

                // refresh timeline
                refreshTimeline();
                stickySidebar();
            }
            localLoader.hide();
        }
    });
});

// follow / unfollow user
$("body").on("click", "[action='followUser']", function(e) {
    // console.log('*** click followUser');

    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_USER,
        'user',
        'follow',
        RETURN_HTML
        );
    
    var context = $(this).closest('.actionFollow');
    var localLoader = $(this).closest('.actionFollow').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = $(this).attr('way');
    // console.log('uuid = '+uuid);
    // console.log('way = '+way);

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: context,
        data: { 'uuid': uuid, 'way': way },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // update follow / unfollow
                $(this).html(data['html']);

                // update reputation counter
                scoreCounter();
                badgesCounter();

                // refresh timeline
                refreshTimeline();
                stickySidebar();
            }
            localLoader.hide();
        }
    });

});

// follow / unfollow tag
$("body").on("click", "[action='followTag']", function(e) {
    // console.log('*** click followTag');
    
    var xhrPath = getXhrPath(
        ROUTE_FOLLOW_TAG,
        'tag',
        'follow',
        RETURN_HTML
        );

    var context = $(this).closest('.actionFollow');
    var localLoader = $(this).closest('.actionFollow').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = $(this).attr('way');
    // console.log('uuid = '+uuid);
    // console.log('way = '+way);

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: context,
        data: { 'uuid': uuid, 'way': way },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // update follow / unfollow
                $(this).html(data['html']);

                // refresh tag sidebar
                userTagListing(
                    $('.sidebarFollowedTags').find('.tagList').first(),
                    $('.sidebarFollowedTags').find('.ajaxLoader').first()
                );
            }
            localLoader.hide();
        }
    });
});

