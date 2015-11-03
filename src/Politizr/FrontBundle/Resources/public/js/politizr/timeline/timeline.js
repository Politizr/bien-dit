// Next page
$("body").on("click", "[action='timelinePaginatedNext']", function(e) {
    timelineList(false, $(this).attr('offset'));
});

// Next page
$("body").on("click", "[action='timelineUserPaginatedNext']", function(e) {
    timelineUserList($(this).attr('userId'), false, $(this).attr('offset'));
});

/**
 * Personal user's timeline
 *
 * @param boolean init
 * @param integer offset
 */
function timelineList(init, offset) {
    // console.log('*** timelineList');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;
    
    var xhrPath = getXhrPath(
        ROUTE_TIMELINE_MINE,
        'user',
        'timelinePaginated',
        RETURN_HTML
        );
    // console.log('xhrPath = '+ xhrPath);
    
    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'offset': offset },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#timelineScrollNav').remove();
                if (init) {
                    $('#listContent').html(data['html']);
                } else {
                    $('#listContent').append(data['html']);
                }

                // maj DOM onSuccess
                stickyDate();
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}

/**
 * User's timeline
 *
 * @param integer userId
 * @param boolean init
 * @param integer offset
 */
function timelineUserList(userId, init, offset) {
    // console.log('*** timelineUserList');
    // console.log(userId);
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;
    
    var xhrPath = getXhrPath(
        ROUTE_TIMELINE_MINE,
        'user',
        'timelineUserPaginated',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'userId': userId, 'offset': offset },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#timelineScrollNav').remove();
                if (init) {
                    $('#listContent').html(data['html']);
                } else {
                    $('#listContent').append(data['html']);
                }

                // maj DOM onSuccess
                // stickyDate();
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}
