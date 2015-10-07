// search result pagination
$("body").on("click", "[action='paginateNext']", function(e) {
    console.log('*** click paginateNext');
    console.log('page = ' + $(this).attr('page'));

    e.preventDefault();

    var xhrPath = getXhrPath(
        ROUTE_SEARCH,
        'search',
        'search',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'query': $(this).attr('query'), 'page': $(this).attr('page') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#ajaxGlobalLoader').hide();
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#scroll-nav').remove();
                $('.boxResults').last().after(data['html']);
                $('#ajaxGlobalLoader').hide();
            }
        }
    });
});
