// accentued characters
var normalize = function( term ) {
    var ret = "";
    for ( var i = 0; i < term.length; i++ ) {
        ret += accentMap[ term.charAt(i) ] || term.charAt(i);
    }
    return ret;
};

/**
 * Init autoloading tag on input search form field
 */
function initInputSearchByTags() {
    console.log('*** initInputSearchByTags');

    // autocomplete initialization
    var xhrPath = getXhrPath(
        ROUTE_TAG_SEARCH_LISTING,
        'tag',
        'getSearchTags',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'tagTypeId': '', 'zoneId': 'searchByTag' },
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
                    item['value'] = val.id;

                    availableTags.push(item);
                });

                // http://api.jqueryui.com/autocomplete/
                $('#searchByTag').find('.selectedTag').first().autocomplete({
                    focus: function(event, ui){
                        event.preventDefault();
                        $('#searchInput').val(ui.item.label);
                    },
                    source: function( request, response ) {
                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
                        response( $.grep( availableTags, function( value ) {
                            value = value.label || value.value || value;
                            return matcher.test( value ) || matcher.test( normalize( value ) );
                        }));
                    },
                    select: function (event, ui) {
                        event.preventDefault();

                        // Affichage de la sélection
                        $('#searchByTag').find('.selectedTag').first().val(ui.item.label);     // display the selected text
                        $('#searchByTag').find('.selectedTagID').first().val(ui.item.value);   // save selected id to hidden input

                        // Auto add selected search tag
                        addSearchTag(ui.item.value);
                    }
                });
            }
        }
    })
};

/**
 * Add search tag
 *
 * @param int id
 */
function addSearchTag(id) {
    console.log('addSearchTag');
    console.log('id = ' + id);

    if (typeof id === "undefined") {
        return false;
    }

    var xhrPath = getXhrPath(
        ROUTE_SEARCH_TAG_ADD,
        'search',
        'addSearchTag',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'tagId': id },
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

                $('#searchByTag').find('.searchTagList').first().append(data['htmlTag']);

                // Init inputs tag
                $('#searchByTag').find('.selectedTag').first().val('');
                $('#searchByTag').find('.selectedTagID').first().val('');
            }
        }
    });
};

/**
 * Delete search tag
 */
$("body").on("click", "[action='deleteSearchTag']", function() {
    console.log('click deleteTag');

    var tagId = $(this).attr('tagId');
    var deleteUrl = $(this).attr('path');

    console.log('tagId = ' + tagId);
    console.log('deleteUrl = ' + deleteUrl);

    $.ajax({
        type: 'POST',
        url: deleteUrl,
        data: { 'tagId': tagId },
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

