// on document ready
$(function() {
    publicationList();
});

// Next page
$("body").on("click", "[action='listingPaginatedNext']", function(e) {
    publicationList(false, $(this).attr('offset'));
});

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
                fullImgLiquid();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}
