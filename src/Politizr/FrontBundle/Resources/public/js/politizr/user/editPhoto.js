// on document ready
$(function() {
    $("#formUserPhoto").ajaxForm(photoOptions);
    $("#formUserBackPhoto").ajaxForm(backPhotoOptions);
    $('.showIllustrationShadow').hide();
});

// open upload modal
$("body").on("click", "[action='modalBackUploadOpen']", function() {
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
// Clic button upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    // console.log('click file select');
    $("#fileName").trigger('click');
    return false;
});

// Form submit upload photo
$("body").on("change", "#fileName", function() {
    // console.log('change file name');
    $('#formUserPhoto').submit();
});

// Click button upload back photo local
$("body").on("click", "[action='backFileSelect']", function() {
    // console.log('click back file select');
    $("#backFileName").trigger('click');
    return false;
});

// Form submit upload back photo
$("body").on("change", "#backFileName", function() {
    // console.log('change back file name');
    $('#formUserBackPhoto').submit();
});


// **************************** //
//      VARIABLES
// **************************** //
// Options Jquery Form JS

// Profile photo
var photoOptions = {
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

// Back photo
var backPhotoOptions = {
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
            $('#uploadedBackPhoto').html(data['html']);
            fullImgLiquid();

            $('#user_back_photo_info_back_file_name, #reaction_photo_info_file_name').val(data['fileName']);
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

    var xhrPath = getXhrPath(
        ROUTE_USER_PHOTO_DELETE,
        'user',
        'userPhotoDelete',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        data: $("#formUserBackPhotoInfoUpdate").serialize(),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // update & imgLiquid uploaded photo
                $('#uploadedPhoto').html(data['html']);
                fullImgLiquid();
            }
        }
    });
});

// Delete back photo
$('body').on('click', "[action='backFileDelete']", function(e){
    // console.log('*** click back file delete');

    $('#uploadedBackPhoto').html('');

    // + ImgLiquid removing
    $('#currentPhoto').removeClass();
    $('#currentPhoto').removeAttr('style');

    $('#user_back_photo_info_back_file_name').val(null);
});

// Save user back photo info
$("body").on("click", "[action='userBackPhotoInfoSave']", function(e) {
    // console.log('*** click save user back photo info');

    var copyright = copyrightEditor.serialize();
    // console.log(copyright['element-0']['value']);

    $('#user_back_photo_info_copyright').val(copyright['element-0']['value']);

    var xhrPath = getXhrPath(
        ROUTE_USER_BACK_PHOTO_INFO_UPDATE,
        'user',
        'userBackPhotoInfoUpdate',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        data: $("#formUserBackPhotoInfoUpdate").serialize(),
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
                $('.profileHeaderIllustration').html(data['imageHeader']);
                $('#illustration_l').html(data['imageHeader']);
                $('#illustration_r').html(data['imageHeader']);
                fullImgLiquid();

                $("[action='modalUploadClose']").trigger('click');
            }
        }
    });
});
