// Timeline's next page
$("body").on("click", "[action='timelinePaginatedNext']", function(e, waypoint) {
    // console.log('timelinePaginatedNext next');
    if (waypoint) {
        waypoint.destroy();
        // console.log('destroy waypoint instance');
    }
    timelineList(false, $(this).attr('offset'));
});

/**
 * Init a waypoint for paginate next
 */
function initTimelinePaginateNextWaypoint() {
    // console.log('initTimelinePaginateNextWaypoint');
    // console.log('create waypoint instance');

    var waypoints = $('#moreResults').waypoint({
        handler: function(direction) {
            // console.log('Hit moreResults');
            // console.log(direction);

            if (direction == 'down') {
                $("[action='timelinePaginatedNext']").trigger( "click", this );
            }
        },
        offset: 'bottom-in-view'
    });
}

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
    
    localLoader = $('.myfeed').find('.ajaxLoader').first();

    xhrCall(
        document,
        { 'offset': offset },
        xhrPath,
        localLoader
    ).done(function(data) {
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
            initTimelinePaginateNextWaypoint();
            fullImgLiquid();
        }
        localLoader.hide();
    });
}
