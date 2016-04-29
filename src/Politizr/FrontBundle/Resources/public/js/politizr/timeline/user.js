// beta

// on document ready
$(function() {
    timelineUserList($('#timeline').attr('uuid'));
});

// User's timeline next page
$("body").on("click", "[action='timelineUserPaginatedNext']", function(e, waypoint) {
    // console.log('timelinePaginatedNext next');
    if (waypoint) {
        waypoint.destroy();
    }
    timelineUserList($(this).attr('uuid'), false, $(this).attr('offset'));
});

/**
 * Init a waypoint for paginate next
 */
function initTimelineUserPaginateNextWaypoint() {
    // console.log('initTimelineUserPaginateNextWaypoint');

    var waypoints = $('#moreResults').waypoint({
        handler: function(direction) {
            // console.log('Hit moreResults');
            // console.log(direction);

            if (direction == 'down') {
                $("[action='timelineUserPaginatedNext']").trigger( "click", this );
            }
        },
        offset: 'bottom-in-view'
    });
}

/**
 * User's timeline
 *
 * @param string uuid
 * @param boolean init
 * @param integer offset
 */
function timelineUserList(uuid, init, offset) {
    // console.log('*** timelineUserList');
    // console.log(uuid);
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
        data: { 'uuid': uuid, 'offset': offset },
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

                // Waypoint for infinite scrolling 
                initTimelineUserPaginateNextWaypoint();

                // maj DOM onSuccess
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}
