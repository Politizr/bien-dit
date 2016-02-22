$(function() {
    $.when(
        organizationListing(
            $('.sidebarListOrg').find('.orgList').first(),
            $('.sidebarListOrg').find('.ajaxLoader').first(),
            $('.pseudoTabs').attr('uuid')
        )
    ).done(function(r1) {
        $(".currentPage[action='documentsByOrganizationListing']").trigger("click");
        stickySidebar();
    });
});

// listing
$("body").on("click", "[action='documentsByOrganizationListing']", function() {
    // console.log('*** click documentsByOrganizationListing');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    documentsByOrganizationListing();
});
