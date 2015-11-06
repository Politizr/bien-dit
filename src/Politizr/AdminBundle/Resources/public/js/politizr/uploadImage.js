// on document ready
$(function() {
    if (typeof uploadZones === "undefined") {
        uploadZones = 1;
    }

    for (uploadZone = 1; uploadZone <= uploadZones; uploadZone++) {
        console.log('init zone '+uploadZone);

        $("#formUploadImage"+uploadZone).ajaxForm(options);
    }
});

// Delete message
$("body").on("click", ".uploadMessage", function() {
    $(this).html('');
});

// Upload simple
$("body").on("change", ".fileName", function() {
    console.log('change file name');
    $(this).closest("form").submit();
});

// Options Jquery Form JS > upload ajax
var options = {
    // iframe: true,
    beforeSend: function() 
    {
        console.log('*** beforeSend');
        console.log(this);
        $('.uploadedMessage').html('Upload en cours...');
    },
    success: function(responseText, statusText, xhr, form)
    {
        console.log('*** success');
        console.log(responseText);
        
        data = $.parseJSON( responseText );
        console.log(data['html']);

        // console.log(statusText);
        // console.log(xhr);
        console.log(form);

        if ( 'success' == statusText ) {
            console.log('status success');
            $(form).prev('.uploadedImage').html(data['html']);
        }

    },
    error: function(data)
    {
        console.log('*** error');
        console.log( data );
        $('.uploadedMessage').html('Upload en erreur!');
    }
}; 

// Delete image
$('body').on('click', "[action='fileDelete']", function(e){
    console.log('*** click file delete');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_DELETE_IMAGE,
        'document',
        'adminImageDelete',
        RETURN_BOOLEAN
        );

    var id = $(this).attr('subjectId');
    var queryClass = $(this).attr('queryClass');
    var setter = $(this).attr('setter');

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        context: this,
        data: { 'id': id, 'queryClass': queryClass, 'setter': setter },
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('.uploadedMessage').html('Suppression terminée');
                $(this).closest('.photoDelete').prevAll('.uploadedImage').html('');
            }
        }
    });
});
