// beta
$(function() {
    $(".currentPage[action='documentsByTagListing']").trigger("click");

    $.when(
        typeTagListing(
            $('.sidebarTypeTags').find('.tagList').first(),
            $('.sidebarTypeTags').find('.ajaxLoader').first()
        ),
        familyTagListing(
            $('.sidebarFamilyTags').find('.tagList').first(),
            $('.sidebarFamilyTags').find('.ajaxLoader').first()
        ),
        // topTagListing(
        //     $('.sidebarTopTags').find('.tagList').first(),
        //     $('.sidebarTopTags').find('.ajaxLoader').first()
        // ),
        userTagListing(
            $('.sidebarFollowedTags').find('.tagList').first(),
            $('.sidebarFollowedTags').find('.ajaxLoader').first()
        ),
        topDocumentListing(
            $('.sidebarTopPosts').find('.documentList').first(),
            $('.sidebarTopPosts').find('.ajaxLoader').first()
        )
    ).done(function(r1, r2, r3) {
        stickySidebar(true);
    });
});

// listing
$("body").on("click", "[action='documentsByTagListing']", function() {
    // console.log('*** click documentsByTagListing');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    documentsByTagListing();
});
