// beta
// use bubble
var timer;
var delay = 1000;

$("body").on("mouseover", "[action='bubbleProfile']", function(e) {
    console.log('*** mouseover bubbleProfile');

    var jqThis = $(this);
    timer = setTimeout(function() {
        console.log('*** setTimeout complete');

        $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.
        $("#siblingsSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.

        var context = jqThis.siblings('.bubblesProfile').first();
        var xhrPath = getXhrPath(
            ROUTE_BUBBLE_USER,
            'bubble',
            'user',
            RETURN_HTML
        );

        var uuid = jqThis.attr('uuid');
        console.log('uuid = '+uuid);

        var localLoader = context.find('.ajaxLoader').first();

        // display bubble
        context.fadeIn(400, function() {
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
    }, delay);
});

$("body").on("mouseleave", ".bubblesProfile, .profileNameHolder", function() {
    console.log('*** mouseleave bubblesProfile');

    $(".bubblesProfile").clearQueue().hide();
    clearTimeout(timer);
});

// // tag bubble
// // toggle profiles bubble
// $(".tag").mouseover(function(){
//     $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.
//     $(this).children('.bubblesTag').delay(500).fadeIn();
// });

$("body").on("mouseover", "[action='bubbleTag']", function() {
    console.log('*** mouseover bubbleTag');

    var jqThis = $(this);
    timer = setTimeout(function() {
        console.log('*** setTimeout complete');

        $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.

        var context = jqThis.siblings('.bubblesTag').first();
        var xhrPath = getXhrPath(
            ROUTE_BUBBLE_TAG,
            'bubble',
            'tag',
            RETURN_HTML
        );

        var uuid = jqThis.attr('uuid');
        // console.log('uuid = '+uuid);

        var localLoader = context.find('.ajaxLoader').first();

        // display bubble
        context.fadeIn(400, function() {
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
    }, delay);
});

$("body").on("mouseleave", ".bubblesTag, .tag", function() {
    console.log('*** mouseleave bubblesTag');

    $(".bubblesTag").clearQueue().hide();
    clearTimeout(timer);
});