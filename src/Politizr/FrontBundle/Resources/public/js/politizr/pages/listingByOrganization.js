// beta
$(function() {
    $(".currentPage[action='documentsByOrganization']").trigger("click");

    $.when(
        organizationListing(
            $('.sidebarListOrg').find('.orgList').first(),
            $('.sidebarListOrg').find('.ajaxLoader').first(),
            $('.currentPage').attr('uuid')
        )
    ).done(function(r1, r2, r3) {
        stickySidebar(true);
    });

});

// listing publications
$("body").on("click", "[action='documentsByOrganization']", function() {
    // console.log('*** click documentsByOrganization');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    uuid = $(this).attr('uuid');
    targetElement = $('.list');

    $.when(
        documentTabsByOrganization(uuid, targetElement)
    ).done(function(r1) {
        documentsByOrganizationListing();
    });
});

// listing users
$("body").on("click", "[action='usersByOrganization']", function() {
    // console.log('*** click usersByOrganization');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    uuid = $(this).attr('uuid');
    targetElement = $('.list');

    $.when(
        userTabsByOrganization(uuid, targetElement)
    ).done(function(r1) {
        usersByOrganizationListing();
    });
});


// filtering publications
$("body").on("click", "[action='documentsByOrganizationFiltering']", function() {
    // console.log('*** click documentsByOrganizationFiltering');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    documentsByOrganizationListing();
});

// filtering users
$("body").on("click", "[action='usersByOrganizationFiltering']", function() {
    // console.log('*** click usersByOrganizationFiltering');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    $('#userListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    usersByOrganizationListing();
});
