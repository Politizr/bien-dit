// beta

// show / hide connection / lost password boxes
$("body").on("click", "[action='showLostPasswordBox']", function(e) {
    // console.log('*** click showLostPasswordBox');

    $('#lostPasswordForm').show();
    $('#loginForm').hide();
});

$("body").on("click", "[action='showLoginBox']", function(e) {
    // console.log('*** click showLoginBox');

    $('#lostPasswordForm').hide();
    $('#loginForm').show();
});

/**
 * Connexion
 */
$("body").on("click", "[action='login']", function(e) {
    // console.log('*** click login');
    $('#ajaxGlobalLoader').show();
    $("#formLogin").submit();
});

/**
 * Lost password
 */
$("body").on("click", "[action='reinitPassword']", function(e) {
    // console.log('*** click reinitPassword');

    return lostPasswordCheck($("#formLostPassword"));
});
