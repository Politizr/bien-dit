// on document ready
$(function() {
    scoreCounter();
})

/**
 * Load reputation score
 */
function scoreCounter(){
    // console.log('*** scoreCounter');

    var xhrPath = getXhrPath(
        ROUTE_SCORE_COUNTER,
        'user',
        'reputationScore',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        success: function(data) {
            $('#fameCounter').html(data['html']);
        }
    });
}
