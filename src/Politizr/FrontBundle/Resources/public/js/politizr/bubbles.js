// beta
var bubbleDelay = 500;

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
    context.delay(bubbleDelay).fadeIn(400, function() {
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
                fullImgLiquid();
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
    context.delay(bubbleDelay).fadeIn(400, function() {
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

// Bubbles help
$("body").on("click", "[action='toogleHelperBubble']", function(e) {
    // console.log('*** action toogleHelperBubble');

    if ($(this).closest('.helper').find('.bubblesHelper').is(':visible')) {
        // console.log('bubblesHelper visible');
        $('.bubblesHelper').hide();
        $('.helperTitle').removeClass("activeHelper");
    } else {
        // console.log('bubblesHelper not visible');
        $('.bubblesHelper').hide();
        $('.helperTitle').removeClass("activeHelper");
        $(this).next('.bubblesHelper').toggle(); // do the toggle       
        $(this).toggleClass("activeHelper"); // toggle  color        
    }
});

// Bubbles badge
$("body").on("mouseover", ".badgeHolder", function() {
    $(this).children('.bubblesBadge').delay(bubbleDelay).fadeIn();
});
$("body").on("mouseleave", ".bubblesBadge, .badgeHolder", function() {
    $(".bubblesBadge").clearQueue().hide();
});

