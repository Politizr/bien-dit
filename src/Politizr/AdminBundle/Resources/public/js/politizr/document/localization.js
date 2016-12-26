// user profile perso update
$("body").on("click", "a[action='updateDocLocalization']", function(e) {
    console.log('click updateDocLocalization');

    var form = $(this).closest('form');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_DOC_LOC,
        'admin',
        'documentLocalization',
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
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('La localisation du document a été mise à jour.');
            $('#infoBoxHolder .boxSuccess').show();
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['errors']);
            $('#infoBoxHolder .boxError').show();
        }
        $('#ajaxGlobalLoader').hide();
    });
});
