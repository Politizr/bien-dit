// beta
$(function() {
    // hide city filter by default
    $('#cityFilter').hide();

    usersByFiltersListing();
    
    stickySidebar();
});


// Map selection
$("body").on("click", "[action='map']", function() {
    // console.log('*** click map');

    $('#cityFilter').hide();

    uuid = $(this).attr('uuid');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');

    return usersMapFiltering(uuid);
});

// Map's selection city
$("body").on("click", "[action='usersMyCity']", function() {
    // console.log('*** click usersMyCity');

    $('#cityFilter').show();

    uuid = $(this).attr('uuid');

    $.when(
        // update breadcrumb
        mapBreadcrumb(uuid),
        // update map
        mapSchema(uuid)
    ).then(function(r1, r2) {
        $('#documentListing .listTop').html('');
        $("[action='goUp']").trigger("click");
        return usersByFiltersListing();
    }).done(function(r1) {
        // remove selected department
        $("[action='map']").removeClass('current');
    });

});

// Map's selection shortcut
$("body").on("click", "[action='usersMyMap']", function() {
    // console.log('*** click usersMyMap');

    $('#cityFilter').hide();

    uuid = $(this).attr('uuid');

    return usersMapFiltering(uuid);
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
function usersMapFiltering(uuid) {
    // console.log('*** usersMapFiltering');
    // console.log(uuid);

    $.when(
        // update breadcrumb
        mapBreadcrumb(uuid),
        // update map
        mapSchema(uuid)
    ).done(function(r1, r2) {
        $('#documentListing .listTop').html('');
        $("[action='goUp']").trigger("click");
        return usersByFiltersListing();
    });
}

