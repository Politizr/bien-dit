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


