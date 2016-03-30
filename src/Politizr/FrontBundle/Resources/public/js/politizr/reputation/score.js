// on document ready

/**
 * Load current user's reputation score
 */
function scoreCounter()
{
    // console.log('*** scoreCounter');

    var uuid = $('#reputCounter').attr('uuid');

    getUserScore(uuid).done(function(data) {
        // console.log(data['html']);
        $('#reputCounter').find('.reputPoints').html(data['html']);
    });
}

/**
 * Load current user's badges score
 */
function badgesCounter()
{
    // console.log('*** badgesCounter');

    var uuid = $('#reputCounter').attr('uuid');

    countUserBadges(uuid).done(function(data) {
        // console.log(data['nbBronze']);
        // console.log(data['nbSilver']);
        // console.log(data['nbGold']);
        $('#reputCounter').find('.badgesCounterBronze').html(data['nbBronze']);
        $('#reputCounter').find('.badgesCounterSilver').html(data['nbSilver']);
        $('#reputCounter').find('.badgesCounterGold').html(data['nbGold']);
    });
}

/**
 * Get user reputation score
 * @param uuid
 */
function getUserScore(uuid)
{
    // console.log('*** getUserScore');
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_SCORE_COUNTER,
        'user',
        'reputationScore',
        RETURN_HTML
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath
    );
}


/**
 * Get user reputation score
 * @param uuid
 */
function countUserBadges(uuid)
{
    // console.log('*** countUserBadges');
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_BADGES_COUNTER,
        'user',
        'badgesScore',
        RETURN_HTML
        );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath
    );
}
