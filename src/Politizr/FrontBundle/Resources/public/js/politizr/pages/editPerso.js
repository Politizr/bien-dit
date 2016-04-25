// beta

// Options Jquery Form JS > upload ajax
var options = {
    // iframe: true,
    beforeSend: function() 
    {
        $('#ajaxGlobalLoader').show();
    },
    success: function(responseText, statusText, xhr, form)
    {
        data = $.parseJSON( responseText );
        console.log(data);

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
    $("#formIdCheckPhoto").ajaxForm(options);
    stickySidebar();
});

// Clic bouton upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    console.log('click file select');
    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    console.log('change file name');
    $('#formIdCheckPhoto').submit();
});



// id check validate ZLA
$("body").on("click", "button[action='validateIdZla']", function(e) {
    // console.log('click submitPerso');

    var form = $(this).closest('form');

    return validateIdZla(form);
});
