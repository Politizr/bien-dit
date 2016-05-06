// beta

// Options Jquery Form JS > upload ajax
var idCheckPhotoOptions = {
    // iframe: true,
    beforeSend: function() 
    {
        $('#ajaxGlobalLoader').show();
    },
    success: function(responseText, statusText, xhr, form)
    {
        data = $.parseJSON( responseText );
        // console.log(data);

        if ( 'success' == statusText && data['success']) {
            validateIdPhoto(data['fileName']);
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        }
    },
    error: function(data)
    {
        $('#ajaxGlobalLoader').hide();
        $('#infoBoxHolder .boxError .notifBoxText').html('Erreur inconnue: merci de recharger la page.');
        $('#infoBoxHolder .boxError').show();
    }
};

$(function() {
    $("#formIdCheckPhoto").ajaxForm(idCheckPhotoOptions);
});

// Clic bouton upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    // console.log('click file select');

    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    // console.log('change file name');

    $('#formIdCheckPhoto').submit();
});

// id check validate ZLA
$("body").on("click", "button[action='validateIdZla']", function(e) {
    // console.log('click submitPerso');

    var form = $(this).closest('form');
    var localLoader = $('#idCheck').find('.ajaxLoader').first();

    return validateIdZla(form, localLoader);
});


/**
 * Id check / validate ZLA
 * @param form
 */
function validateIdZla(form, localLoader)
{
    // console.log('click validateIdZla');
    // console.log(form);
    // console.log(localLoader);

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
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['success']) {
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Votre identité a été validée.');
                $('#infoBoxHolder .boxSuccess').show();
            } else {
                $('#infoBoxHolder .boxError .notifBoxText').html('Votre identité n\'a pas été validée.');
                $('#infoBoxHolder .boxError').show();
            }
        }
        localLoader.hide();
    });
}

/**
 * Id check / validate photo
 * @param
 */
function validateIdPhoto(fileName)
{
    // console.log('click validateIdPhoto');
    // console.log(fileName);

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

