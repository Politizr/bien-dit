// on document ready
$(function() {
    notificationsLoading();
})

// // ouverture/fermeture box notifications
// $("body").on("click", "[action='linkNotifications']", function() {
//     $('#notifications').slideToggle();
// });
// 
// mobile : hide menus when opening notifications
$("body.css760").on("touchstart click", "[action='linkNotifications']", function(e) {
    if(e.type == "touchstart") { // if touchstart start toggle
        $('#headerCenter, #menu').hide();
        e.stopPropagation();
        e.preventDefault(); // stop touchstart 
        return false;
    } else if(e.type == "click") { // if click : do the same, but don't trigger touchstart
        $('#headerCenter, #menu').hide();               
    }
});

$("body.css760").on("touchstart click", "[action='linkNotifications']", function(e) {
    if(e.type == "touchstart") { // if touchstart start toggle
        $('#headerCenter, #menu').hide();
        e.stopPropagation();
        e.preventDefault(); // stop touchstart 
    return false;
    } else if(e.type == "click") { // if click : do the same, but don't trigger touchstart
        $('#headerCenter, #menu').hide();
    }
});

$("body").on("click", ".notifClose", function() {
    $('#notifications').slideUp('fast');
});


// Regular function with arguments
function notificationsLoading(){
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
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();

            // MAJ compteur
            $('#notifCounter').html(data['counterNotifs']);

            // MAJ listing des notifs
            $('#notifList').html(data['html']);
        }
    });

    // Rappel toutes les 60 secondes
    setTimeout(function(){
        notificationsLoading();
    }, 60000);
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
        data: { 'subjectId': $(this).attr('subjectId') },
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();

            $(this).find('.notifHighlight').removeClass();
            $(this).find('.iconCheck').remove();
            $(this).addClass('viewedNotif');

            $('#notifCounter').html(parseInt($('#notifCounter').text()) - 1);

            $('#ajaxGlobalLoader').hide();
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
        data: { 'subjectId': $(this).closest('span').attr('subjectId') },
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

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        context: $(this).closest('table'),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            // MAJ du style
            $('.notifItem').addClass('viewedNotif');
            $('.notifItem').find('.notifHighlight').removeClass();
            $('.notifItem').find('.iconCheck').remove();

            // MAJ du compteur
            $('#notifCounter').html('-');

            $('#ajaxGlobalLoader').hide();
        }
    });

})
