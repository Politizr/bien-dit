// beta
$(function() {
    $(".currentPage[action='documentsByUserListing']").trigger("click");

    $.when(
        updateSidebarBadges(),
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

/**
 * Update sidebarBadges div if exists
 */
function updateSidebarBadges()
{
    var divUserBadges = $('.sidebarBadges').find('#userBadges').first();
    if (!divUserBadges.is(':visible')) {
        return;
    }

    var uuid = $('#userBadges').attr('uuid');

    getUserScore(uuid).done(function(data) {
        // console.log(data['html']);
        $('.sidebarBadges').find('h5').first().html(data['html'] + ' points de réputation');
    });

    return userMiniBadgeListing(
        $('.sidebarBadges').find('#userBadges').first(),
        $('.sidebarBadges').find('.ajaxLoader').first(),
        uuid
    );
}