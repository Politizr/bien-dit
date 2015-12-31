// on document ready
$(function() {
    $("#formDocumentPhoto").ajaxForm(options);
    $('.showIllustrationShadow').hide();
});

// open upload modal
$("body").on("click", "[action='modalUploadOpen']", function() {
    $('#modalUploadBox').show();
    $('#modalUpload').slideDown('fast');
    $('body').addClass('noscroll');
    $('html, body').animate({
        scrollTop: $("body").offset().top
    }, 0);
});

// close upload modal 
$("body").on("click", "[action='modalUploadClose']", function() {
    $('body').removeClass('noscroll');
    $('#modalUploadBox').hide();
    $('#modalUpload').slideUp();
});

// **************************** //
//      LISTENERS
// **************************** //
// Clic bouton upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    // console.log('click file select');
    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    // console.log('change file name');
    $('#formDocumentPhoto').submit();
});


// **************************** //
//      VARIABLES
// **************************** //
// Options Jquery Form JS > upload ajax
var options = {
    // iframe: true,
    beforeSend: function() 
    {
        $('#ajaxGlobalLoader').show();
    },
    success: function() 
    {
        $('#ajaxGlobalLoader').hide();
    },
    complete: function(data) 
    {
        data = data.responseText;
        // console.log('data.responseText = ' + data);

        // Gestion retour iframe IE
        data = data.replace('<PRE>', '');
        data = data.replace('</PRE>', '');
        data = data.replace('<pre>', '');
        data = data.replace('</pre>', '');
        // console.log('data replace = ' + data);
        data = $.parseJSON( data );
        // console.log('data parse JSON = ' + data);

        if (data['success']) {
            // update & imgLiquid uploaded photo
            $('#uploadedPhoto').html(data['html']);
            fullImgLiquid();

            $('#debate_photo_info_file_name, #reaction_photo_info_file_name').val(data['fileName']);
        } else if (data['error']) {
            // functional error
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

// Delete photo
$('body').on('click', "[action='fileDelete']", function(e){
    // console.log('*** click file delete');

    $('#uploadedPhoto').html('');

    // + ImgLiquid removing
    $('#currentPhoto').removeClass();
    $('#currentPhoto').removeAttr('style');

    $('#debate_photo_info_file_name, #reaction_photo_info_file_name').val(null);
});

// Save debate photo info
$("body").on("click", "[action='debatePhotoInfoSave']", function(e) {
    // console.log('*** click save debate photo info');

    var copyright = copyrightEditor.serialize();
    // console.log(copyright['element-0']['value']);

    $('#debate_photo_info_copyright').val(copyright['element-0']['value']);

    var xhrPath = getXhrPath(
        ROUTE_DEBATE_PHOTO_INFO_UPDATE,
        'document',
        'debatePhotoInfoUpdate',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        data: $("#formDebatePhotoInfoUpdate").serialize(),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // update copyright info
                $('#copyright').html(data['copyright']);

                // update & imgLiquid uploaded photo
                // console.log(data['imageHeader']);
                $('#illustrationMain').html(data['imageHeader']);
                $('#illustration_l').html(data['imageHeader']);
                $('#illustration_r').html(data['imageHeader']);
                fullImgLiquid();

                $("[action='modalUploadClose']").trigger('click');
            }
        }
    });
});

// Save reaction photo info
$("body").on("click", "[action='reactionPhotoInfoSave']", function(e) {
    // console.log('*** click save reaction photo info');

    var copyright = copyrightEditor.serialize();
    // console.log(copyright['element-0']['value']);

    $('#reaction_photo_info_copyright').val(copyright['element-0']['value']);

    var xhrPath = getXhrPath(
        ROUTE_REACTION_PHOTO_INFO_UPDATE,
        'document',
        'reactionPhotoInfoUpdate',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        data: $("#formReactionPhotoInfoUpdate").serialize(),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // update copyright info
                $('#copyright').html(data['copyright']);

                // update & imgLiquid uploaded photo
                // console.log(data['imageHeader']);
                $('#illustrationMain').html(data['imageHeader']);
                $('#illustration_l').html(data['imageHeader']);
                $('#illustration_r').html(data['imageHeader']);
                fullImgLiquid();

                $("[action='modalUploadClose']").trigger('click');
            }
        }
    });
});
