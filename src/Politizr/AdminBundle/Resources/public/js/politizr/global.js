// Fermeture d'une box
$("body").on("click", ".closeAdminBox", function(e) {
    console.log('*** click closeAdminBox');
    
    // cache parent div
    $(this).closest('div').hide();
});
