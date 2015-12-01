// Fermeture d'une box
$("body").on("click", "[action='closeBox']", function(e) {
    // console.log('*** click closeBox');
    
    // cache parent div
    $(this).closest('div').hide();
});
