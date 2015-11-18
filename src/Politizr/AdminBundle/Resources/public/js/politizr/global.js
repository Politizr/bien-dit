// Fermeture d'une box
$('body').on('click', "[action='closeBox']", function(e){
    // cache parent div
    $(this).closest('div').hide();
});

