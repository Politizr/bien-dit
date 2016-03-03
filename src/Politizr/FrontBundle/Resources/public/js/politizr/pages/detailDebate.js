// beta
$(function() {
    $.when(
        lastDebateFollowersListing(
            $('.sidebarSubjectFollowers').find('#subjectFollowers').first(),
            $('.sidebarSubjectFollowers').find('.ajaxLoader').first(),
            $('#subjectFollowers').attr('uuid')
        )
    ).done(function(r1) {
        stickySidebar();
    });
});
