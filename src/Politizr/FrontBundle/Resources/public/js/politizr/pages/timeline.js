// TIMELINE USER
// on document ready
$(function() {
    // suggestion documents listing
    suggestionDocumentListing(
        $('#suggestions').find('.documentList').first(),
        $('#suggestions').find('.ajaxLoader').first()
    );

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

    // timeline
    timelineList();

    // sticky sidebar
    var sticky = new Waypoint.Sticky({
        element: $('#sidebar'),
        offset: 'bottom-in-view'
    })
});
