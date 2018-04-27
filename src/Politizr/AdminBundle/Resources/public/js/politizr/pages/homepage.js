// send admin notif
$("body").on("click", "[action='createAdminNotif']", function(e) {
    console.log('*** click createAdminNotif');
    
    if (confirm('Êtes-vous sûr?')) {
        return createAdminNotification();
    }
});

// filterForm
$("body").on("click", "a[action='filterForm']", function(e) {
    console.log('click filterForm');

    var targetElement = $('#adminNotifForm');
    var serializedForms = $("#filterUsers, #formNotification").serialize();

    $.when(
        filterAdminNotifUsersList(targetElement, serializedForms)
    ).done(function(data) {
        $('textarea.tinymce').tinymce().remove();
        $('textarea.tinymce').tinymce(tinyMceAttr);
    });
});

/**
 * Update users form
 */
function filterAdminNotifUsersList(targetElement, serializedForms) {
    console.log('*** filterAdminNotifUsersList');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_NOTIF_FILTER_USERS,
        'dashboard',
        'filterAdminNotifUsers',
        RETURN_HTML
    );

    return xhrCall(
        document,
        serializedForms,
        xhrPath
    ).done(function(data) {
        console.log(data);
        targetElement.html(data['html']);
        $('#ajaxGlobalLoader').hide();
    });
}

