// beta

// Options Jquery Form JS > upload ajax
var options = {
    // iframe: true,
    beforeSend: function() 
    {
        $('#ajaxGlobalLoader').show();
        $('.actionSave').removeClass('saved');
    },
    success: function(responseText, statusText, xhr, form)
    {
        data = $.parseJSON( responseText );
        // console.log('data.responseText = ' + data);

        if ( 'success' == statusText ) {
            // update & imgLiquid uploaded photo
            $('#uploadedPhoto').html(data['html']);
            fullImgLiquid();

            $('#debate_file_name, #reaction_file_name').val(data['fileName']);

            triggerSaveDocument();
        }

        $('#ajaxGlobalLoader').hide();
    },
    error: function(data)
    {
        $('#ajaxGlobalLoader').hide();
        $('#infoBoxHolder .boxError .notifBoxText').html('Erreur inconnue: merci de recharger la page.');
        $('#infoBoxHolder .boxError').show();
    }
}; 
