// beta
$(function() {
    publicationsByFiltersListing();
    
    stickySidebar();
});

// Map selection
$("body").on("click", "[action='map']", function() {
    // console.log('*** click map');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByFiltersListing();
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
