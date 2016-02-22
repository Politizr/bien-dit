// TIMELINE USER
// on document ready
$(function() {
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
        $(".currentPage[action='documentByTagListing']").trigger("click");
        stickySidebar();
    });
});

// listing
$("body").on("click", "[action='documentByTagListing']", function() {
    // console.log('*** click documentByTagListing');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    documentsByTagListing();
});
