$(function() {
    // modal city/dep/region/country selection show/hide
    locShowHideAttr();
});

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

// change checkbox type event
$(':radio').on('change', function() {
    console.log('*** radio change');
    locShowHideAttr();
});


/**
 * Show / hide div attributes relative to zone choice
 */
function locShowHideAttr() {
    console.log('*** locShowHideAttr');

    if ($('#document_localization_loc_type_0').is(':checked')) {
        console.log('document_localization_loc_type_0 :checked');
        $('#document_localization_localization_city').show();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_1').is(':checked')) {
        console.log('document_localization_loc_type_1 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').show();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_2').is(':checked')) {
        console.log('document_localization_loc_type_2 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').show();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_3').is(':checked')) {
        console.log('document_localization_loc_type_3 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').hide();
    } else if ($('#document_localization_loc_type_4').is(':checked')) {
        console.log('document_localization_loc_type_4 :checked');
        $('#document_localization_localization_city').hide();
        $('#document_localization_localization_department').hide();
        $('#document_localization_localization_region').hide();
        $('#document_localization_localization_circonscription').show();
    }
}
