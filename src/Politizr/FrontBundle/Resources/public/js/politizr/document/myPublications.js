// on document ready
$(function() {
    publicationList();
});

// Next page
$("body").on("click", "[action='listingPaginatedNext']", function(e, waypoint) {
    // console.log('paginate next');
    if (waypoint) {
        waypoint.destroy();
    }
    publicationList(false, $(this).attr('offset'));
});

/**
 * Init a waypoint for paginate next
 */
function initMyPublicationsPaginateNextWaypoint() {
    // console.log('initMyPublicationsPaginateNextWaypoint');

    var waypoints = $('#moreResults').waypoint({
        handler: function(direction) {
            // console.log('Hit moreResults');
            // console.log(direction);

            if (direction == 'down') {
                $("[action='listingPaginatedNext']").trigger( "click", this );
            }
        },
        offset: 'bottom-in-view'
    });
}

/**
 * User's contribution listing
 *
 * @param boolean init
 * @param integer offset
 */
function publicationList(init, offset) {
    // console.log('*** publicationList');
    // console.log(init);
    // console.log(offset);

    init = (typeof init === "undefined") ? true : init;
    offset = (typeof offset === "undefined") ? 0 : offset;
    
    var xhrPath = getXhrPath(
        xhrRoute,
        'document',
        xhrMethod,
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'offset': offset },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            // console.log('success');
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
            }
            $('#ajaxGlobalLoader').hide();

            // Waypoint for infinite scrolling
            // initMyPublicationsPaginateNextWaypoint();

            // maj DOM onSuccess
            fullImgLiquid();
        }
    });
}