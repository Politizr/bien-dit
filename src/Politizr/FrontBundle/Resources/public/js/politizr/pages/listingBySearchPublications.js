// beta
$(function() {
    currentUuid = $('#localizationPreload').attr('uuid');
    userUuid = $("#linkMyRegion").attr('uuid');
    
    if (currentUuid) {
        $("#localizationPreload").trigger("click");
    } else if (userUuid) {
        $("#linkMyRegion").trigger("click");
    } else {
        publicationsByFiltersListing();
    }
    
    stickySidebar();
});


// Map selection
$("body").on("click", "[action='map']", function() {
    // console.log('*** click map');
    uuid = $(this).attr('uuid');
    type = $(this).attr('type');

    $("[action='publicationsMyMap']").removeClass('currentPage');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    
    $.when(
        // update breadcrumb
        mapBreadcrumb(uuid, type),
        // update map
        mapSchema(uuid, type)
    ).done(function(r1, r2) {
        $('#documentListing .listTop').html('');
        $("[action='goUp']").trigger("click");
        return publicationsByFiltersListing();
    });
});

// Map's selection shortcut
$("body").on("click", "[action='publicationsMyMap']", function() {
    // console.log('*** click publicationsMyMap');

    $(this).siblings().removeClass('currentPage');
    $(this).addClass('currentPage');

    uuid = $(this).attr('uuid');
    type = $(this).attr('type');
    if (uuid) {
        $.when(
            // update menu
            mapMenu(uuid, type)
        ).then(function(r1, r2) {
            return publicationsMapFiltering(uuid, type);
        });
    }
});


// Publication filter change
$("body").on("change", ".publicationFilter", function() {
    // console.log('*** change publicationFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

// Profile filter change
$("body").on("change", ".profileFilter", function() {
    // console.log('*** change profileFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

// Activity filter change
$("body").on("change", ".activityFilter", function() {
    // console.log('*** change activityFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});

// Date filter change
$("body").on("change", ".dateFilter", function() {
    // console.log('*** change dateFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
});



/**
 * Trigger the map & publication's listing reloading
 *
 * @param string
 */
function publicationsMapFiltering(uuid, type) {
    // console.log('*** publicationsMapFiltering');
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
        return publicationsByFiltersListing();
    });
}
