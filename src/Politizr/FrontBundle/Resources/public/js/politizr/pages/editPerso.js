// beta

$(function() {
    stickySidebar();
});

// user profile perso update
$("body").on("click", "button[action='submitPerso']", function(e) {
    // console.log('click submitPerso');

    var form = $(this).closest('form');
    var localLoader = $(this).closest('.formBlock').find('.ajaxLoader').first();

    return saveUserPerso(form, localLoader);
});

// Delete account
$('body').on('click', "[action='deleteAccount']", function(e){
    // console.log('*** click deleteAccount');
    
    var path = $(this).attr('target');
    // console.log(path);

    var confirmMsg = "Votre compte va être supprimé. Êtes-vous sûr?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            // redirection
            window.location = path;
        }
    }, {
        ok: "Supprimer mon compte",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

