
// clic ajout nouveau tag
$("body").on("click", "[action='userAssociateTag']", function() {
    // console.log('*** click userAssociateTag');

    var uuid = $(this).attr('uuid');
    // console.log('uuid = ' + uuid);

    var xhrPath = getXhrPath(
        ROUTE_TAG_USER_ASSOCIATE,
        'tag',
        'userAssociateTag',
        RETURN_HTML
        );

    var localLoader = $(this).siblings('.ajaxLoader').first();

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'uuid': uuid },
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
                $('#userTagAssociateZone').html(data['html']);
            }
        }
    });
});