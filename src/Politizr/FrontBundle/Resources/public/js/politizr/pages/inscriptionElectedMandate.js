// Mandate creation
$("body").on("click", "[action='mandateCreate']", function(e) {
    // console.log('*** click mandateCreate');

    return createUserMandate();
});

// Mandate update
$("body").on("click", "[action='mandateUpdate']", function(e) {
    // console.log('*** click mandateUpdate');

    var form = $(this).closest('#formMandateUpdate');
    var localLoader = $(this).closest('.myMandate').find('.ajaxLoader').first();
    return saveUserMandate(form, localLoader);
});

// Mandate deletion
$("body").on("click", "[action='mandateDelete']", function(e) {
    // console.log('*** click mandateDelete');
    var uuid = $(this).attr('uuid');
    var localLoader = $(this).closest('.myMandate').find('.ajaxLoader').first();
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer ce mandat?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return deleteUserMandate(uuid, localLoader);
        }
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
    });
});

