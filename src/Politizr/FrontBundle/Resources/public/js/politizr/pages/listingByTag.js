$(function() {
    $(".currentPage[action='documentsByTagListing']").trigger("click");

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
    });
});

// listing
$("body").on("click", "[action='documentsByTagListing']", function() {
    // console.log('*** click documentsByTagListing');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    documentsByTagListing();
});
