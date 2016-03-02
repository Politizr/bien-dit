// Abuse reporting
$("body").on("click", "[action='abuseReporting']", function() {
    if ($('#formAbuse').is(":visible")) {
        $('.sidebarReportForm').slideToggle();
        $('#formAbuse').html('');
    } else {
        var uuid = $(this).attr('uuid');
        var type = $(this).attr('type');
        var localLoader = $('.sidebarReportForm').find('.ajaxLoader').first();
        $('.sidebarReportForm').slideToggle(400, 'swing', loadFormAbuse(uuid, type, localLoader));
    }
}); 

$("body").on("click", "[action='closeAbuseReporting']", function() {
    $('.sidebarReportForm').slideToggle();
    $('#formAbuse').html('');
});

$("body").on("click", "[action='createAbuseReporting']", function() {
    var localLoader = $('.sidebarReportForm').find('.ajaxLoader').first();

    $.when(
        validateFormAbuse(localLoader)
    ).done(function(r1) {
        if (!r1['error']) {
            $('.sidebarReportForm').slideToggle();
            $('#formAbuse').html('');
        }
    });
});


/**
 * Form abuse box loading
 */
function loadFormAbuse(uuid, type, localLoader) {
    console.log('*** loadFormAbuse');
    uuid = (typeof uuid === "undefined") ? null : uuid;
    type = (typeof type === "undefined") ? null : type;

    if (uuid == null || type == null) {
        return false;
    }

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ABUSE,
        'monitoring',
        'abuse',
        RETURN_HTML
        );

    return xhrCall(
        document,
        { 'uuid': uuid, 'type': type },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#formAbuse').html(data['html']);
        }
        localLoader.hide();
    });
}


/**
 * Form abuse box loading
 */
function validateFormAbuse(localLoader) {
    console.log('*** validateFormAbuse');

    var xhrPath = getXhrPath(
        ROUTE_MONITORING_ABUSE_CHECK,
        'monitoring',
        'abuseCreate',
        RETURN_BOOLEAN
        );

    return xhrCall(
        document,
        $("#formAbuseReporting").serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Merci, nos équipes vont étudier votre signalement rapidement.');
            $('#infoBoxHolder .boxSuccess').show();
        }
        localLoader.hide();
    });
}
