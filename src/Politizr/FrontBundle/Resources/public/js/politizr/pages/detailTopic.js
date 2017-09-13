// beta
$(function() {
    publicationsByTopicListing();
    stickySidebar();
});

// Publication filter change
$("body").on("change", ".publicationFilter", function() {
    // console.log('*** change publicationFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByTopicListing();
});

// Profile filter change
$("body").on("change", ".profileFilter", function() {
    // console.log('*** change profileFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByTopicListing();
});

// Activity filter change
$("body").on("change", ".activityFilter", function() {
    // console.log('*** change activityFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByTopicListing();
});

// Date filter change
$("body").on("change", ".dateFilter", function() {
    // console.log('*** change dateFilter');

    $('#documentListing .listTop').html('');
    $("[action='goUp']").trigger("click");

    return publicationsByTopicListing();
});
