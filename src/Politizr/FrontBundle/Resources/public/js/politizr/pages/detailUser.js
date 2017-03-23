// beta
$(function() {
    $(".currentPage[action='publicationsByUserListing']").trigger("click");

    $.when(
        showReputation(),
        userMiniBadgeListing(
            $('.sidebarBadges').find('#userBadges').first(),
            $('.sidebarBadges').find('.ajaxLoader').first(),
            $('#userBadges').attr('uuid')
        ),
        lastUserFollowersListing(
            $('.sidebarUserFollowers').find('#userFollowers').first(),
            $('.sidebarUserFollowers').find('.ajaxLoader').first(),
            $('#userFollowers').attr('uuid')
        ),
        lastUserSubscribersListing(
            $('.sidebarUserSubscribers').find('#userSubscribers').first(),
            $('.sidebarUserSubscribers').find('.ajaxLoader').first(),
            $('#userSubscribers').attr('uuid')
        ),
        userTagListing(
            $('.sidebarFollowedTags').find('.tagList').first(),
            $('.sidebarFollowedTags').find('.ajaxLoader').first()
        )
    ).done(function(r1, r2, r3) {
        stickySidebar();
    });
});

// Tag filtering
$("body").on("click", "[action='filterByTag']", function(e) {
    // console.log('*** click filterByTag');

    $('.filterByTags .tag a').removeAttr('class');
    $(this).attr('class', 'active');

    $(".currentPage[action='publicationsByUserListing']").trigger("click");
});

