// use bubble
$("body").on("mouseover", "[action='bubbleProfile']", function() {
    console.log('*** mouseover bubbleProfile');

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

    // display bubble
    context.delay(500).fadeIn(400, function() {
        $.ajax({
            type: 'POST',
            url: xhrPath,
            context: context,
            data: { 'uuid': uuid },
            dataType: 'json',
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
            statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
            success: function(data) {
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    context.find('.bubbleContent').html(data['html']);
                }
                localLoader.hide();
            }
        });
    });
});

$("body").on("mouseleave", ".bubblesProfile, .profileNameHolder", function() {
    console.log('*** mouseleave bubblesProfile');

    $(".bubblesProfile").clearQueue().hide();
});

// tag bubble
    // toggle profiles bubble
    $(".tag").mouseover(function(){
        $("#suggSlide.cycle-slideshow").css("overflow", "visible"); // In #suggestion : cycle2 hide the overflow during scrollHoriz, hiding the tag bubbles. This line forces it back to visible.
        $(this).children('.bubblesTag').delay(500).fadeIn();
    });

$("body").on("mouseover", "[action='bubbleTag']", function() {
    console.log('*** mouseover bubbleTag');

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

    // display bubble
    context.delay(500).fadeIn(400, function() {
        $.ajax({
            type: 'POST',
            url: xhrPath,
            context: context,
            data: { 'uuid': uuid },
            dataType: 'json',
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
            statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
            success: function(data) {
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    context.find('.bubbleContent').html(data['html']);
                }
                localLoader.hide();
            }
        });
    });
});

$("body").on("mouseleave", ".bubblesTag, .tag", function() {
    console.log('*** mouseleave bubblesTag');

    $(".bubblesTag").clearQueue().hide();
});