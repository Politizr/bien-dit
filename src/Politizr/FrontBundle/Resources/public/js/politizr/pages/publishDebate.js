// TAG vars
var nbZones = 1;
var service = 'tag';
var xhrRoute = ROUTE_TAG_LISTING;
var xhrUrlPrefix = '/xhr';

// Publish debate
$('body').on('click', "[action='debatePublish']", function(e){
    // console.log('*** click publish debate');

    $.when(triggerSaveDocument()).done(function(r1) {
        var uuid = $(this).attr('uuid');
        var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre sujet de discussion. Voulez-vous publier votre sujet?";
        smoke.confirm(confirmMsg, function(e) {
            if (e) {
                return publishDebate(uuid);
            }
        }, {
            ok: "Publier",
            cancel: "Annuler"
            // classname: "custom-class",
            // reverseButtons: true
        });
    });
});

