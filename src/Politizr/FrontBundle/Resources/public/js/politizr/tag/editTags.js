// accentued characters
var normalize = function( term ) {
    var ret = "";
    for ( var i = 0; i < term.length; i++ ) {
        ret += accentMap[ term.charAt(i) ] || term.charAt(i);
    }
    return ret;
};

// on document ready
$(function() {
    // autocomplete initialization
    // console.log('nbZones = '+nbZones);

    for (i = 1; i <= nbZones; i++ ) {
        var tagTypeId = $('#editTagZone-'+i).attr('tagTypeId');
        // console.log('tagTypeId = '+tagTypeId);

        var xhrPath = getXhrPath(
            xhrRoute,
            service,
            'getTags',
            RETURN_HTML
            );

        $.ajax({
            type: 'POST',
            url: xhrPath,
            data: { 'tagTypeId': tagTypeId, 'zoneId': i },
            dataType: 'json',
            // context: $('#editTagZone-'+i),
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
            statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
            success: function(data) {
                $('#ajaxGlobalLoader').hide();
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    var availableTags = [];
                    $.each( data['tags'], function( key, val ) {
                        var item = [];
                        item['label'] = val.title;
                        item['value'] = val.id;

                        availableTags.push(item);
                    });

                    var zoneId = data['zoneId'];

                    // http://api.jqueryui.com/autocomplete/
                    $('#editTagZone-'+zoneId).children('.selectedTag').first().autocomplete({
                    // $('.selectedTag').autocomplete({
                        focus: function(event, ui){
                            event.preventDefault();
                            $('#editTagZone-'+zoneId).children('.selectedTag').first().val(ui.item.label);
                        },
                        source: function( request, response ) {
                            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                            response( $.grep( availableTags, function( value ) {
                                value = value.label || value.value || value;
                                return matcher.test( value ) || matcher.test( normalize( value ) );
                            }));
                        },
                        select: function (event, ui) {
                            event.preventDefault();

                            // Affichage de la sélection
                            $('#editTagZone-'+zoneId).children('.selectedTag').first().val(ui.item.label);     // display the selected text
                            $('#editTagZone-'+zoneId).children('.selectedTagID').first().val(ui.item.value);   // save selected id to hidden input
                        }
                    });
                }
            }
        })
    }
});

// clic ajout nouveau tag
$("body").on("click", "button[action='addTag']", function() {
    // console.log('click addTag');

    var tagTitle = $(this).siblings('.selectedTag').first().val();
    var tagId = $(this).siblings('.selectedTagID').first().val();

    var tagTypeId = $(this).closest('.addTag').attr('tagTypeId');
    var subjectId = $(this).closest('.addTag').attr('subjectId');
    var newTag = $(this).closest('.addTag').attr('newTag');
    var addUrl = $(this).closest('.addTag').attr('path');

    // console.log('title = ' + tagTitle);
    // console.log('id = ' + tagId);
    // console.log('type = ' + tagTypeId);
    // console.log('subjectId = ' + subjectId);
    // console.log('newTag = ' + newTag);
    // console.log('url = ' + addUrl);

    tag = tagTitle.trim();
    if (tag === '') {
        return false;
    }

    // Ajout du nouveau tag
    $.ajax({
        type: 'POST',
        url: addUrl,
        data: { 'tagTitle': tagTitle, 'tagId': tagId, 'tagTypeId': tagTypeId, 'subjectId': subjectId, 'newTag': newTag },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        context: this,
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#ajaxLoader').hide();

                if (data['created']) {
                    // Association du nouveau tag aux tags suivis
                    $(this).siblings('.editableTagList').first().append(data['htmlTag']);
                }

                // Init inputs tag
                $(this).siblings('.selectedTag').first().val('');
                $(this).siblings('.selectedTagID').first().val('');
            }
        }
    });
});

// clic suppression tag
$("body").on("click", "[action='deleteTag']", function() {
    // console.log('click deleteTag');

    var tagId = $(this).attr('tagId');
    var subjectId = $(this).attr('subjectId');;
    var deleteUrl = $(this).attr('path');

    // console.log('tagId = ' + tagId);
    // console.log('subjectId = ' + subjectId);
    // console.log('deleteUrl = ' + deleteUrl);

    $.ajax({
        type: 'POST',
        url: deleteUrl,
        data: { 'subjectId': subjectId, 'tagId': tagId },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        context: $(this).parent(),
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#ajaxLoader').hide();

                $(this).remove();
            }
        }
    });
});
