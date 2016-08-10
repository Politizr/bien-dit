// beta

$(function() {
    // autocomplete initialization
    // console.log('nbZones = '+nbZones);

    for (i = 1; i <= nbZones; i++ ) {
        return initTagZoneAutoComplete($('#editTagZone-'+i));
    }
});

// clic ajout nouveau tag
$("body").on("click", "button[action='addTag']", function() {
    // console.log('click addTag');

    var contextZone = $(this).closest('.addTag');

    return createTagAssociation(contextZone);
});

// clic suppression tag
$("body").on("click", "[action='deleteTag']", function() {
    // console.log('click deleteTag');

    var contextZone = $(this).closest('.tagLabel');

    return deleteTagAssociation(contextZone);
});

// clic suppression tag
$("body").on("click", "[action='hideTag']", function() {
    // console.log('click hideTag');

    var contextZone = $(this).closest('.tagLabel');

    return hideTagAssociation(contextZone);
});

/**
 * Init tag autocompleting for a context zone tag
 *
 * @param contextZone
 */
function initTagZoneAutoComplete(contextZone)
{
    // console.log('*** initTagZoneAutoComplete');
    // console.log(contextZone);

    var tagTypeId = contextZone.attr('tagTypeId');
    // console.log('tagTypeId = '+tagTypeId);

    var localLoader = contextZone.find('.ajaxLoader').first();
    var xhrPath = getXhrPath(
        xhrRoute,
        service,
        'getTags',
        RETURN_HTML
        );

    return xhrCall(
        contextZone,
        { 'tagTypeId': tagTypeId },
        xhrPath,
        localLoader
    ).done(function(data) {
        $('#ajaxGlobalLoader').hide();
        localLoader.hide();
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

            return initAutoComplete(contextZone, availableTags, '.selectedTag', '.selectedTagUuid');
        }
    });
}

/**
 * Create a tag association with a debate / reaction / user
 */
function createTagAssociation(contextZone)
{
    // console.log('*** createTagAssociation');
    // console.log(contextZone);

    var tagTitle = contextZone.find('.selectedTag').first().val();
    var tagUuid = contextZone.find('.selectedTagUuid').first().val();

    var tagTypeId = contextZone.attr('tagTypeId');
    var uuid = contextZone.attr('uuid');
    var newTag = contextZone.attr('newTag');
    var withHidden = contextZone.attr('withHidden');
    var addUrl = contextZone.attr('path');

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

    var localLoader = contextZone.find('.ajaxLoader').first();

    return xhrCall(
        contextZone,
        { 'tagTitle': tagTitle, 'tagUuid': tagUuid, 'tagTypeId': tagTypeId, 'uuid': uuid, 'newTag': newTag, 'withHidden': withHidden },
        addUrl,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['created']) {
                // Association du nouveau tag aux tags suivis
                contextZone.find('.editableTagList').first().append(data['htmlTag']);
            }

            // reinit inputs tag
            contextZone.find('.selectedTag').first().val('');
            contextZone.find('.selectedTagUuid').first().val('');
        }
        localLoader.hide();
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Delete a tag association with a debate / reaction / user
 *
 * @param contextZone
 */
function deleteTagAssociation(contextZone)
{
    // console.log('*** deleteTagAssociation');
    // console.log(contextZone);

    var tagUuid = contextZone.attr('tagUuid');
    var uuid = contextZone.attr('uuid');;
    var deleteUrl = contextZone.find("[action='deleteTag']").first().attr('path');

    // console.log('tagUuid = ' + tagUuid);
    // console.log('uuid = ' + uuid);
    // console.log('deleteUrl = ' + deleteUrl);

    var localLoader = contextZone.closest('.addTag').find('.ajaxLoader').first();

    return xhrCall(
        contextZone,
        { 'uuid': uuid, 'tagUuid': tagUuid },
        deleteUrl,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            contextZone.remove();
        }
        localLoader.hide();
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 * Hide a tag association with a debate / reaction / user
 *
 * @param contextZone
 */
function hideTagAssociation(contextZone)
{
    // console.log('*** hideTagAssociation');
    // console.log(contextZone);

    var tagUuid = contextZone.attr('tagUuid');
    var uuid = contextZone.attr('uuid');;
    var hideUrl = contextZone.find("[action='hideTag']").first().attr('path');

    // console.log('tagUuid = ' + tagUuid);
    // console.log('uuid = ' + uuid);
    // console.log('hideUrl = ' + hideUrl);

    var localLoader = contextZone.closest('.addTag').find('.ajaxLoader').first();

    return xhrCall(
        contextZone,
        { 'uuid': uuid, 'tagUuid': tagUuid },
        hideUrl,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['success'] == true) {
                contextZone.addClass( "tagInvisible" );
            } else {
                contextZone.removeClass( "tagInvisible" );
            }
        }
        localLoader.hide();
        $('#ajaxGlobalLoader').hide();
    });
}
