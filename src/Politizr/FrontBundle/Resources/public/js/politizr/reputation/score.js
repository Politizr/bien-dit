// on document ready
$(function() {
    scoreCounter();
    badgesCounter();
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
            $('.reputPoints').html(data['html']);
        }
    });
}

/**
 * Load badges score
 */
function badgesCounter(){
    // console.log('*** badgesCounter');

    var xhrPath = getXhrPath(
        ROUTE_BADGES_COUNTER,
        'user',
        'badgesScore',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        success: function(data) {
            $('.badgesCounterBronze').html(data['nbBronze']);
            $('.badgesCounterSilver').html(data['nbSilver']);
            $('.badgesCounterGold').html(data['nbGold']);
        }
    });
}
