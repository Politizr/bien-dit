// beta
$(function() {
    publicationsByFiltersListing();
    
    stickySidebar();
});


// Map selection
$("body").on("click", "[action='map']", function() {
    // console.log('*** click map');
    uuid = $(this).attr('uuid');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    
    $.when(
        // update breadcrumb
        mapBreadcrumb(uuid),
        // update map
        mapSchema(uuid)
    ).done(function(r1, r2) {
        $('#documentListing .listTop').html('');
        $("[action='goUp']").trigger("click");
        return publicationsByFiltersListing();
    });
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

