// user profile perso update
$("body").on("click", "a[action='updateLocalization']", function(e) {
    console.log('click updateLocalization');

    var form = $(this).closest('form');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_USER_CITY,
        'admin',
        'userCity',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        'POST'
    ).done(function(data) {
        console.log(data);
        if (data['success']) {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('La ville de l\'utilisateur a été mise à jour.');
            $('#infoBoxHolder .boxSuccess').show();
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['errors']);
            $('#infoBoxHolder .boxError').show();
        }
        $('#ajaxGlobalLoader').hide();
    });
});
