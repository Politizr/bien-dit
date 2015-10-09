// @todo JS actually not plugged

// Context email notification debate subscribe
$("body").on("click", "span[action='notif-subscribe']", function(e) {
    // console.log('*** click notif-subscribe');

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CONTEXT_DEBATE_SUBSCRIBE,
        'notification',
        'notifDebateContextSubscribe',
        RETURN_BOOLEAN
        );
    
    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'subjectId': $(this).attr('subject-id'), 'context': $(this).attr('context') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // @todo deprecated?
                $(this).attr('class', 'fa fa-check-square-o');
                $(this).attr('action', 'notif-unsubscribe');
            }
        }
    });
});

// Context email notification debate unsubscribe
$("body").on("click", "span[action='notif-unsubscribe']", function(e) {
    // console.log('*** click notif-unsubscribe');
    
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CONTEXT_DEBATE_UNSUBSCRIBE,
        'notification',
        'notifDebateContextUnsubscribe',
        RETURN_BOOLEAN
        );
    
    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'subjectId': $(this).attr('subject-id'), 'context': $(this).attr('context') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $(this).attr('class', 'fa fa-square-o');
                $(this).attr('action', 'notif-subscribe');
            }
        }
    });
});

// Context email notification user subscribe
$("body").on("click", "span[action='notif-subscribe']", function(e) {
    // console.log('*** click notif-subscribe');

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CONTEXT_USER_SUBSCRIBE,
        'notification',
        'notifUserContextSubscribe',
        RETURN_BOOLEAN
        );
            
    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'subjectId': $(this).attr('subject-id'), 'context': $(this).attr('context') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // @todo deprecated?
                $(this).attr('class', 'fa fa-check-square-o');
                $(this).attr('action', 'notif-unsubscribe');
            }
        }
    });
});

// Context email notification user unsubscribe
$("body").on("click", "span[action='notif-unsubscribe']", function(e) {
    // console.log('*** click notif-unsubscribe');
    
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CONTEXT_USER_UNSUBSCRIBE,
        'notification',
        'notifUserContextSubscribe',
        RETURN_BOOLEAN
        );
            
    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'subjectId': $(this).attr('subject-id'), 'context': $(this).attr('context') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $(this).attr('class', 'fa fa-square-o');
                $(this).attr('action', 'notif-subscribe');
            }
        }
    });
});
