// beta
$(function() {
    uuid = $("#linkMyRegion").attr('uuid');
    if (uuid) {
        $("#linkMyRegion").trigger("click");
    } else {
        usersByFiltersListing();
    }    

    stickySidebar();
});


// Map selection
$("body").on("click", "[action='map']", function() {
    // console.log('*** click map');

    $('#cityFilter').hide();

    uuid = $(this).attr('uuid');
    type = $(this).attr('type');

    $("[action='usersMyCity']").removeClass('currentPage');
    $("[action='usersMyMap']").removeClass('currentPage');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');

    return usersMapFiltering(uuid, type);
});


// Map's selection shortcut
$("body").on("click", "[action='usersMyMap']", function() {
    // console.log('*** click usersMyMap');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    $('#cityFilter').hide();

    uuid = $(this).attr('uuid');
    type = $(this).attr('type');

    if (uuid) {
        $.when(
            // update menu
            mapMenu(uuid, type)
        ).then(function(r1, r2) {
            return usersMapFiltering(uuid, type);
        });
    }
});

// Publication filter change
$("body").on("change", ".publicationFilter", function() {
    // console.log('*** change publicationFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return usersByFiltersListing();
});

// Profile filter change
$("body").on("change", ".profileFilter", function() {
    // console.log('*** change profileFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return usersByFiltersListing();
});

// Activity filter change
$("body").on("change", ".activityFilter", function() {
    // console.log('*** change activityFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return usersByFiltersListing();
});

// Date filter change
$("body").on("change", ".dateFilter", function() {
    // console.log('*** change dateFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return usersByFiltersListing();
});



/**
 * Trigger the map & user's listing reloading
 *
 * @param string
 */
function usersMapFiltering(uuid, type) {
    // console.log('*** usersMapFiltering');
    // console.log(uuid);
    // console.log(type);

    $.when(
        // update breadcrumb
        mapBreadcrumb(uuid, type),
        // update map
        mapSchema(uuid, type)
    ).done(function(r1, r2) {
        $('#documentListing .listTop').html('');
        $("[action='goUp']").trigger("click");
        return usersByFiltersListing();
    });
}

