// beta
var bubbleDelay = 500;
var timeoutId;

/**
 * Load bubble
 *
 * @param xhrPath
 * @param context
 * @param uuid
 * @param localLoader
 */
function loadBubble(xhrPath, context, uuid, localLoader) {
    timeoutId = setTimeout(function() {
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
                context.show().find('.bubbleContent').html(data['html']);
                fullImgLiquid();
            }
            localLoader.hide();
        });
      }, bubbleDelay);
}

/**
 * Profile bubble
 */
$("body").on("mouseenter", "[action='bubbleProfile']", function() {
    console.log('*** mouseenter bubbleProfile');

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
    console.log('uuid = '+uuid);

    var localLoader = context.find('.ajaxLoader').first();

    return loadBubble(xhrPath, context, uuid, localLoader);
});

$("body").on("mouseleave", ".bubblesProfile, .profileNameHolder, .avatar40", function() {
    console.log('*** mouseleave bubblesProfile');

    clearTimeout(timeoutId);
    $(".bubblesProfile").clearQueue().hide();
});

/**
 * Tag bubble
 */
$("body").on("mouseenter", "[action='bubbleTag']", function() {
    console.log('*** mouseenter bubbleTag');

    $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.

    var context = $(this).siblings('.bubblesTag').first();
    var xhrPath = getXhrPath(
        ROUTE_BUBBLE_TAG,
        'bubble',
        'tag',
        RETURN_HTML
    );

    var uuid = $(this).attr('uuid');
    console.log('uuid = '+uuid);

    var localLoader = context.find('.ajaxLoader').first();

    return loadBubble(xhrPath, context, uuid, localLoader);
});

$("body").on("mouseleave", ".bubblesTag, .tag", function() {
    console.log('*** mouseleave bubblesTag');

    clearTimeout(timeoutId);
    $(".bubblesTag").clearQueue().hide();
});

// Bubbles help
$("body").on("click", "[action='toogleHelperBubble']", function(e) {
    console.log('*** action toogleHelperBubble');

    if ($(this).closest('.helper').find('.bubblesHelper').is(':visible')) {
        console.log('bubblesHelper visible');
        $('.bubblesHelper').hide();
        $('.helperTitle').removeClass("activeHelper");
    } else {
        console.log('bubblesHelper not visible');
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

