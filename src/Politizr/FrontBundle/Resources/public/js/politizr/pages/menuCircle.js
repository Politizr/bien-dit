// beta
$(function() {
    $.when(
        typeTagListing(
            $('.sidebarTypeTags').find('.tagList').first(),
            $('.sidebarTypeTags').find('.ajaxLoader').first()
        ),
        // topTagListing(
        //     $('.sidebarTopTags').find('.tagList').first(),
        //     $('.sidebarTopTags').find('.ajaxLoader').first()
        // ),
        familyTagListing(
            $('.sidebarFamilyTags').find('.tagList').first(),
            $('.sidebarFamilyTags').find('.ajaxLoader').first()
        ),
        userTagListing(
            $('.sidebarFollowedTags').find('.tagList').first(),
            $('.sidebarFollowedTags').find('.ajaxLoader').first()
        ),
        topDocumentListing(
            $('.sidebarTopPosts').find('.documentList').first(),
            $('.sidebarTopPosts').find('.ajaxLoader').first()
        )
    ).done(function(r1, r2, r3, r4) {
        stickySidebar();
    });
});

// Join circle
$("body").on("click", "[action='joinCircle']", function(e) {
    console.log('*** click joinCircle');

    var targetElement = $(this).closest('.circleActions');
    var localLoader = $(this).closest('.withLoaderInside').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = 'subscribe';

    return subscribeCircle(targetElement, localLoader, uuid, way);
});

// Quit circle
$("body").on("click", "[action='quitCircle']", function(e) {
    console.log('*** click quitCircle');
    
    var targetElement = $(this).closest('.circleActions');
    var localLoader = $(this).closest('.withLoaderInside').find('.ajaxLoader').first();
    var uuid = $(this).attr('uuid');
    var way = 'unsubscribe';

    var confirmMsg = "Vous allez quitter ce groupe. Êtes-vous sûr?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return subscribeCircle(targetElement, localLoader, uuid, way);
        }
    }, {
        ok: "Quitter le groupe",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});
