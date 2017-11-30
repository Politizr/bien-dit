$(function() {
    console.log('hello world');
});

// filterForm
$("body").on("click", "a[action='filterForm1']", function(e) {
    console.log('click filterForm1');

    var serializedForms = $("#filterUsers1, #users1").serialize();
    filterCircleUsersList(serializedForms);

    // $("#moderationAlertNew").serialize(),
});

/**
 * Search operation localizations
 */
function filterCircleUsersList(serializedForms) {
    console.log('*** filterCircleUsersList');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_CIRCLE_FILTER_USERS,
        'admin',
        'filterCircleUsers',
        RETURN_HTML
    );

    return xhrCall(
        document,
        serializedForms,
        xhrPath
    ).done(function(data) {
        console.log(data);
        $('#ajaxGlobalLoader').hide();
    });
}

