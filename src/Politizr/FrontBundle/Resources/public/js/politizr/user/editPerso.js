// beta
// user profile perso update
$("body").on("click", "button[action='submitPerso']", function(e) {
    // console.log('click submitPerso');

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


// user profile perso update
$("body").on("click", "button[action='validateId']", function(e) {
    // console.log('click submitPerso');

    var form = $(this).closest('form');

    var xhrPath = getXhrPath(
        ROUTE_USER_VALIDATE_ID,
        'user',
        'validateId',
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
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Votre identité a été validée.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});
