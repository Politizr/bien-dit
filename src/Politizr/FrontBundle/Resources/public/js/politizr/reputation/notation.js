// notation
$("body").on("click", "[action='note']", function(e) {
    // console.log('*** click note');
    
    e.preventDefault();

    var localLoader = $(this).closest('.notation').find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        ROUTE_NOTE,
        'document',
        'note',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: $(this).closest('.notation'),
        data: { 'uuid': $(this).attr('uuid'), 'type': $(this).attr('type'), 'way': $(this).attr('way') },
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
                $(this).html(data['html']);

                // update reputation counter
                scoreCounter();
                badgesCounter();
            }
        }
    });

});
