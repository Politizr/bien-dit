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
                        item['value'] = val.uuid;

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
                            $('#editTagZone-'+zoneId).children('.selectedTagUuid').first().val(ui.item.value);   // save selected id to hidden input
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
    var tagUuid = $(this).siblings('.selectedTagUuid').first().val();

    var tagTypeId = $(this).closest('.addTag').attr('tagTypeId');
    var uuid = $(this).closest('.addTag').attr('uuid');
    var newTag = $(this).closest('.addTag').attr('newTag');
    var withHidden = $(this).closest('.addTag').attr('withHidden');
    var addUrl = $(this).closest('.addTag').attr('path');

    // console.log('title = ' + tagTitle);
    // console.log('tagUuid = ' + tagUuid);
    // console.log('type = ' + tagTypeId);
    // console.log('uuid = ' + uuid);
    // console.log('newTag = ' + newTag);
    // console.log('withHidden = ' + withHidden);
    // console.log('url = ' + addUrl);

    tag = tagTitle.trim();
    if (tag === '') {
        return false;
    }

    var localLoader = $(this).siblings('.ajaxLoader').first();

    // Ajout du nouveau tag
    $.ajax({
        type: 'POST',
        url: addUrl,
        data: { 'tagTitle': tagTitle, 'tagUuid': tagUuid, 'tagTypeId': tagTypeId, 'uuid': uuid, 'newTag': newTag, 'withHidden': withHidden },
        dataType: 'json',
        context: this,
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                if (data['created']) {
                    // Association du nouveau tag aux tags suivis
                    $(this).siblings('.editableTagList').first().append(data['htmlTag']);
                }

                // Init inputs tag
                $(this).siblings('.selectedTag').first().val('');
                $(this).siblings('.selectedTagUuid').first().val('');
            }
        }
    });
});

// clic suppression tag
$("body").on("click", "[action='deleteTag']", function() {
    // console.log('click deleteTag');

    var tagUuid = $(this).closest('.tagLabel').attr('tagUuid');
    var uuid = $(this).closest('.tagLabel').attr('uuid');;
    var deleteUrl = $(this).attr('path');

    // console.log('tagUuid = ' + tagUuid);
    // console.log('uuid = ' + uuid);
    // console.log('deleteUrl = ' + deleteUrl);

    var localLoader = $(this).closest('.editableTagList').siblings('.ajaxLoader').first();

    $.ajax({
        type: 'POST',
        url: deleteUrl,
        context: this,
        data: { 'uuid': uuid, 'tagUuid': tagUuid },
        dataType: 'json',
        context: $(this).parent(),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $(this).closest('.tagLabel').remove();
            }
        }
    });
});

// clic suppression tag
$("body").on("click", "[action='hideTag']", function() {
    // console.log('click hideTag');

    var tagUuid = $(this).closest('.tagLabel').attr('tagUuid');
    var uuid = $(this).closest('.tagLabel').attr('uuid');;
    var hideUrl = $(this).attr('path');

    // console.log('tagUuid = ' + tagUuid);
    // console.log('uuid = ' + uuid);
    // console.log('hideUrl = ' + hideUrl);

    var localLoader = $(this).closest('.editableTagList').siblings('.ajaxLoader').first();

    $.ajax({
        type: 'POST',
        url: hideUrl,
        context: this,
        data: { 'uuid': uuid, 'tagUuid': tagUuid },
        dataType: 'json',
        context: $(this).parent(),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                if (data['success'] == true) {
                    $(this).closest('.tagLabel').addClass( "tagInvisible" );
                } else {
                    $(this).closest('.tagLabel').removeClass( "tagInvisible" );
                }
            }
        }
    });
});
