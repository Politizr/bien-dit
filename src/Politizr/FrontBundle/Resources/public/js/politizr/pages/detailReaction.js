// beta
$(function() {
    loadFbInsights();
    loadFbComments();

    stickySidebar();

    $('.paragraph p').selectionSharer();
});


$("body").on("click", "[action='showMenuAllFamily']", function() {
    $('.answerMenuAllFamily').toggle();
    $('.answerShowMenuAllFamily').hide();
    $('.answerHideMenuAllFamily').show();
    
});

$("body").on("click", "[action='hideMenuAllFamily']", function() {
    $('.answerMenuAllFamily').toggle();
    $('.answerHideMenuAllFamily').hide();
    $('.answerShowMenuAllFamily').show();
}); 
