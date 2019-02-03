// beta
$(function() {
    publicationsByTopicListing();
    stickySidebar();
});

// Publication filter change
$("body").on("change", ".publicationFilter", function() {
    // console.log('*** change publicationFilter');

    $('#documentListing .listTop').html('');

    $("[action='hideBrief']").trigger("click");

    // $("[action='goUp']").trigger("click");
    // code not working, have to copy/paste triggered function
    stickySidebar(true);
    $('html, body').animate({
        scrollTop: $("body").offset().top
    }, '1000');

    return publicationsByTopicListing();
});

// Profile filter change
$("body").on("change", ".profileFilter", function() {
    // console.log('*** change profileFilter');

    $('#documentListing .listTop').html('');

    $("[action='hideBrief']").trigger("click");

    // $("[action='goUp']").trigger("click");
    // code not working, have to copy/paste triggered function
    stickySidebar(true);
    $('html, body').animate({
        scrollTop: $("body").offset().top
    }, '1000');

    return publicationsByTopicListing();
});

// Activity filter change
$("body").on("change", ".activityFilter", function() {
    // console.log('*** change activityFilter');

    $('#documentListing .listTop').html('');

    $("[action='hideBrief']").trigger("click");

    // $("[action='goUp']").trigger("click");
    // code not working, have to copy/paste triggered function
    stickySidebar(true);
    $('html, body').animate({
        scrollTop: $("body").offset().top
    }, '1000');

    return publicationsByTopicListing();
});

// Date filter change
$("body").on("change", ".dateFilter", function() {
    // console.log('*** change dateFilter');

    $('#documentListing .listTop').html('');

    $("[action='hideBrief']").trigger("click");

    // $("[action='goUp']").trigger("click");
    // code not working, have to copy/paste triggered function
    stickySidebar(true);
    $('html, body').animate({
        scrollTop: $("body").offset().top
    }, '1000');

    return publicationsByTopicListing();
});
