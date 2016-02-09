// on document ready
$(function() {
    notificationsLoading();
})

$(document).mousedown(function (e) {
    var container = $("#notifBox");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
        $("body").on("click", "[action='toggleNotifBox']", function() {
            $('#notifBox').toggle();
        });
    }
});

// Regular function with arguments
function notificationsLoading(){
    // console.log('*** notificationsLoading');

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_LOADING,
        'notification',
        'notificationsLoad',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        success: function(data) {
            $('#ajaxGlobalLoader').hide();

            // MAJ compteur
            note = parseInt(data['counterNotifs']);
            if (note > 0) {
                $('#notifCounterNew').html(data['counterNotifs']).show();
            } else {
                $('#notifCounterNew').html('-').hide();
            }

            // MAJ listing des notifs
            $('#notifBox').html(data['html']);
        }
    });
}

// check notification
$("body").on("click", "i[action='notificationCheck']", function(e) {
    // console.log('*** click i notificationCheck');

    e.preventDefault();

    var localLoader = $(this).closest('.notifItem').find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CHECK,
        'notification',
        'notificationCheck',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        context: $(this).closest('.notifItem'),
        data: { 'uuid': $(this).attr('uuid') },
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();

            $(this).find('.notifHighlight').removeClass();
            $(this).find('.icon-check-incircle').remove();
            $(this).addClass('viewedNotif');

            note = parseInt($('#notifCounterNew').text()) - 1;
            if (note > 0) {
                $('#notifCounterNew').html(note);
            } else {
                $('#notifCounterNew').html('-').hide();
            }
        }
    });

})

$("body").on("click", "a[action='notificationCheck']", function(e) {
    // console.log('*** click a notificationCheck');

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CHECK,
        'notification',
        'notificationCheck',
        RETURN_BOOLEAN
        );

    e.preventDefault();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        context: this,
        data: { 'uuid': $(this).closest('span').attr('uuid') },
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            window.location = $(this).attr('href');
        }
    });

})

// check toutes les notifications
$("body").on("click", "div[action='notificationCheckAll']", function(e) {
    // console.log('*** click notificationCheckAll');

    var xhrPath = getXhrPath(
        ROUTE_NOTIF_CHECK_ALL,
        'notification',
        'notificationsCheckAll',
        RETURN_BOOLEAN
        );

    var localLoader = $(this).closest('#notifBox').find('.ajaxLoader').first();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        context: $(this).closest('table'),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();

            // MAJ du style
            $('.notifItem').addClass('viewedNotif');
            $('.notifItem').find('.notifHighlight').removeClass();
            $('.notifItem').find('.icon-check-incircle').remove();

            // MAJ du compteur
            $('#notifCounterNew').html('-').hide();
        }
    });

})
