// beta

/**
 * Validate user form
 * @param form
 */
function saveUserPerso(form, localLoader)
{
    // console.log('*** saveUserPerso');
    // console.log(form);
    // console.log(localLoader);

    var xhrPath = getXhrPath(
        ROUTE_USER_PERSO_UPDATE,
        'user',
        'userPersoUpdate',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Les données modifiées ont bien été mises à jour.');
            $('#infoBoxHolder .boxSuccess').show();
        }
        localLoader.hide();
    });
}

