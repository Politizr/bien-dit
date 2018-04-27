$(function() {
});

// filterForm
$("body").on("click", "a[action='filterForm1']", function(e) {
    console.log('click filterForm1');

    var targetElement = $('#circleUsersForms1');
    var serializedForms = $("#formAttr1, #filterUsers1, #users1").serialize();
    filterCircleUsersList(targetElement, serializedForms);
});

$("body").on("click", "a[action='filterForm2']", function(e) {
    console.log('click filterForm2');

    var targetElement = $('#circleUsersForms2');
    var serializedForms = $("#formAttr2, #filterUsers2, #users2").serialize();
    filterCircleUsersList(targetElement, serializedForms);
});

$("body").on("click", "a[action='filterForm3']", function(e) {
    console.log('click filterForm3');

    var targetElement = $('#circleUsersForms3');
    var serializedForms = $("#formAttr3, #filterUsers3, #users3").serialize();
    filterCircleUsersList(targetElement, serializedForms);
});

/**
 * Update users form
 */
function filterCircleUsersList(targetElement, serializedForms) {
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
        targetElement.html(data['html']);
        $('#ajaxGlobalLoader').hide();
    });
}

