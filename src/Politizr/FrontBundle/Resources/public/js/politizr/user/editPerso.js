// beta
// user profile perso update
$("body").on("click", "button[action='submitPerso']", function(e) {
    console.log('click submitPerso');

    var form = $(this).closest('form');

    var xhrPath = getXhrPath(
        ROUTE_USER_PERSO_UPDATE,
        'user',
        'userPersoUpdate',
        RETURN_BOOLEAN
    );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: form.serialize(),
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Les données modifiées ont bien été mises à jour.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});

/**
 * Id check / validate ZLA
 * @param form
 */
function validateIdZla(form)
{
    console.log('click validateIdZla');

    var xhrPath = getXhrPath(
        ROUTE_USER_VALIDATE_ID,
        'user',
        'validateIdZla',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        1
    ).done(function(data) {
        $('#ajaxGlobalLoader').hide();
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#ajaxGlobalLoader').hide();
            if (data['success']) {
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Votre identité a été validée.');
                $('#infoBoxHolder .boxSuccess').show();
            } else {
                $('#infoBoxHolder .boxError .notifBoxText').html('Votre identité n\'a pas été validée.');
                $('#infoBoxHolder .boxError').show();
            }
        }
    });
}

/**
 * Id check / validate photo
 * @param
 */
function validateIdPhoto(fileName)
{
    console.log('click validateIdPhoto');
    console.log(fileName);

    var xhrPath = getXhrPath(
        ROUTE_USER_VALIDATE_PHOTO_UPLOAD,
        'user',
        'validateIdPhoto',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        { 'fileName': fileName },
        xhrPath,
        1
    ).done(function(data) {
        $('#ajaxGlobalLoader').hide();
        if (data['success']) {
            $('#infoBoxHolder .boxSuccess .notifBoxText').html('Votre identité a été validée.');
            $('#infoBoxHolder .boxSuccess').show();
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html('Votre identité n\'a pas été validée.');
            $('#infoBoxHolder .boxError').show();
        }
    });
}

