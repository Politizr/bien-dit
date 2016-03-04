// beta
/**
 * Profile bubble
 */
$("body").on("mouseenter", "[action='bubbleProfile']", function() {
    // console.log('*** mouseenter bubbleProfile');

    $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.
    $("#siblingsSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.

    var context = $(this).siblings('.bubblesProfile').first();
    var xhrPath = getXhrPath(
        ROUTE_BUBBLE_USER,
        'bubble',
        'user',
        RETURN_HTML
    );

    var uuid = $(this).attr('uuid');
    // console.log('uuid = '+uuid);

    var localLoader = context.find('.ajaxLoader').first();

    // display bubble
    context.delay(1000).fadeIn(400, function() {
        return xhrCall(
            context,
            { 'uuid': uuid },
            xhrPath,
            localLoader
        ).done(function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                context.find('.bubbleContent').html(data['html']);
            }
            localLoader.hide();
        });
    });
});

$("body").on("mouseleave", ".bubblesProfile, .profileNameHolder, .avatar40", function() {
    // console.log('*** mouseleave bubblesProfile');

    $(".bubblesProfile").clearQueue().hide();
});

/**
 * Tag bubble
 */
$("body").on("mouseenter", "[action='bubbleTag']", function() {
    // console.log('*** mouseenter bubbleTag');

    $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.

    var context = $(this).siblings('.bubblesTag').first();
    var xhrPath = getXhrPath(
        ROUTE_BUBBLE_TAG,
        'bubble',
        'tag',
        RETURN_HTML
    );

    var uuid = $(this).attr('uuid');
    // console.log('uuid = '+uuid);

    var localLoader = context.find('.ajaxLoader').first();

    // display bubble
    context.delay(1000).fadeIn(400, function() {
        return xhrCall(
            context,
            { 'uuid': uuid },
            xhrPath,
            localLoader
        ).done(function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                context.find('.bubbleContent').html(data['html']);
            }
            localLoader.hide();
        });
    });
});

$("body").on("mouseleave", ".bubblesTag, .tag", function() {
    // console.log('*** mouseleave bubblesTag');

    $(".bubblesTag").clearQueue().hide();
});