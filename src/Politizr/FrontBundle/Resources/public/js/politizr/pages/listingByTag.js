// TIMELINE USER
// on document ready
$(function() {
    console.log('*** init listingByTags page');

    // throw clic action top event
    $(".currentPage[action='documentByTagListing']").trigger("click");

    // top tags listing
    topTagListing(
        $('.sidebarTopTags').find('.tagList').first(),
        $('.sidebarTopTags').find('.ajaxLoader').first()
    );

    // user tags listing
    userTagListing(
        $('.sidebarFollowedTags').find('.tagList').first(),
        $('.sidebarFollowedTags').find('.ajaxLoader').first()
    );

    // top documents listing
    topDocumentListing(
        $('.sidebarTopPosts').find('.documentList').first(),
        $('.sidebarTopPosts').find('.ajaxLoader').first()
    );

    // sticky sidebar
    stickySidebar();
});

// listing
$("body").on("click", "[action='documentByTagListing']", function() {
    console.log('*** click documentByTagListing');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    // @todo initDocumentListingPaginateNextWaypoint
    documentByTagListing();
});
