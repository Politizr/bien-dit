// beta
$(function() {
    var mode = $('#suggestionMode').attr('mode');
    if (mode == 'show') {
        $('#suggestions').show();
        $('.hideSugg').show();
        $('.showSugg').hide();
    } else {
        $('#suggestions').hide();
        $('.hideSugg').hide();
        $('.showSugg').show();
    }

    suggestionDocumentListing(
        $('#suggestions').find('.documentList').first(),
        $('#suggestions').find('.ajaxLoader').first()
    )
    .then( function() {
            timelineList();
            $.when(
                topTagListing(
                    $('.sidebarTopTags').find('.tagList').first(),
                    $('.sidebarTopTags').find('.ajaxLoader').first()
                ),
                userTagListing(
                    $('.sidebarFollowedTags').find('.tagList').first(),
                    $('.sidebarFollowedTags').find('.ajaxLoader').first()
                ),
                topDocumentListing(
                    $('.sidebarTopPosts').find('.documentList').first(),
                    $('.sidebarTopPosts').find('.ajaxLoader').first()
                )
            ).done(function(r1, r2, r3) {
                stickySidebar();
            })
        }
    );
});

// toggle suggestion 
$("body").on("click", "[action='hideSugg']", function() {
    $('#suggestions').hide();
    $(this).hide();
    showSuggestion(false);
    $('#suggestionMode').attr('mode', 'hide');
    $('.showSugg').show();
});

$("body").on("click", "[action='showSugg']", function() {
    $('#suggestions').show();
    $(this).hide();
    showSuggestion(true);
    $('#suggestionMode').attr('mode', 'show');
    $('.hideSugg').show();
});


/**
 * Put show/hide suggestion in session
 * @param targetElement
 * @param localLoader
 * @param uuid
 */
function showSuggestion(show) {
    // console.log('*** showSuggestion');
    // console.log(show);
    
    var xhrPath = getXhrPath(
        ROUTE_USER_DETAIL_CONTENT,
        'general',
        'showSuggestion',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        { 'show': show },
        xhrPath
    ).done(function(data) {
    });
}
