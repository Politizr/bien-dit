// beta

// load document's user listing
$("body").on("click", "[action='publicationsByUserListing']", function() {
    // console.log('*** click documentsByTagListing');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    publicationsByUserListing();
});


// listing user followers
// @todo if plug > waypoint bug (load all listing) > solution = first trigger goUp > to test
$("body").on("click", "[action='listingContentUserFollowers']", function() {
    // console.log('*** click listingContentUserFollowers');

    $.when(
        listingContentUserFollowers(
            $('#content'),
            $('#content').find('.ajaxLoader').first(),
            $(this).attr('uuid')
        )
    ).done(function(r1) {
        updateUrl(r1['uri']);
        userFollowersListing();
    });
});

// listing user subscribers
// @todo if plug > waypoint bug (load all listing)
$("body").on("click", "[action='listingContentUserSubscribers']", function() {
    // console.log('*** click listingContentUserSubscribers');

    $.when(
        listingContentUserSubscribers(
            $('#content'),
            $('#content').find('.ajaxLoader').first(),
            $(this).attr('uuid')
        )
    ).done(function(r1) {
        updateUrl(r1['uri']);
        userSubscribersListing();
    });
});


// back to user detail
// @todo if plug > waypoint bug (load all listing)
$("body").on("click", "[action='detailContentUser']", function() {
    // console.log('*** click detailContentUser');

    $.when(
        detailContentUser(
            $('#content'),
            $('#content').find('.ajaxLoader').first(),
            $(this).attr('uuid')
        )
    ).done(function(r1) {
        updateUrl(r1['uri']);
        $(".currentPage[action='documentsByUserListing']").trigger("click");
    });
});

/**
 * Loading user detail content
 * @param targetElement
 * @param localLoader
 * @param uuid
 */
function detailContentUser(targetElement, localLoader, uuid) {
    // console.log('*** detailContentUser');
    // console.log(targetElement);
    // console.log(localLoader);
    // console.log(uuid);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_DETAIL_CONTENT,
        'user',
        'detailContent',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            targetElement.html(data['html']);
        }
        localLoader.hide();
    });
}
