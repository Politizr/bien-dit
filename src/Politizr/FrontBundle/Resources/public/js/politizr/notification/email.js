// Souscription email
$("body").on("click", "[action='notifEmailSubscribe']", function(e) {
    // console.log('*** click notifEmailSubscribe');
    
    var localLoader = $(this).closest('.notificationsPrefsItem').find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_EMAIL_SUBSCRIBE,
        'notification',
        'notifEmailSubscribe',
        RETURN_BOOLEAN
        );

    var subjectId = $(this).attr('subjectId');
    // console.log(subjectId);

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'subjectId': subjectId },
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
                $(this).attr('checked', 'checked');
                $(this).attr('action', 'notifEmailUnsubscribe');
            }
        }
    });
});

// Désouscription email
$("body").on("click", "[action='notifEmailUnsubscribe']", function(e) {
    // console.log('*** click notifEmailUnsubscribe');
    
    var localLoader = $(this).closest('.notificationsPrefsItem').find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_EMAIL_UNSUBSCRIBE,
        'notification',
        'notifEmailUnsubscribe',
        RETURN_BOOLEAN
        );
            
    var subjectId = $(this).attr('subjectId');
    // console.log(subjectId);

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'subjectId': subjectId },
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
                $(this).removeAttr('checked');
                $(this).attr('action', 'notifEmailSubscribe');
            }
        }
    });
});
