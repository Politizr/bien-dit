// beta

/**
 * Auto save
 * Event = keyup + 2sec
 * http://stackoverflow.com/questions/9966394/can-i-delay-the-keyup-event-for-jquery
 */

/**
 *
 */
function dataRequest() {
    return saveUserProfile();
}

$('#user_biography, #user_website, #user_twitter, #user_facebook').on('keyup', delayRequest);

/**
 *
 */
function delayRequest(ev) {
    // console.log('*** autoSaveDelay');
    $('.actionSave').removeClass('saved');

    if(delayRequest.timeout) {
        clearTimeout(delayRequest.timeout);
    }
    var target = this;
    delayRequest.timeout = setTimeout(function() {
        dataRequest.call(target, ev);
    }, 5000); // 5s
}

function triggerSaveDocument()
{
    // console.log('*** triggerSaveDocument');

    return saveUserProfile();
}

/**
 *
 */
 function deleteUserPhoto()
 {
    // console.log('*** deleteUserPhoto');

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_USER_PHOTO_DELETE,
        'user',
        'userPhotoDelete',
        RETURN_HTML
    );

    return xhrCall(
        document,
        null,
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            // update & imgLiquid uploaded photo
            $('#uploadedPhoto').html(data['html']);
            fullImgLiquid();

            $('#user_file_name').val(null);

            saveUserProfile();
        }
        localLoader.hide();
    });   
 }

/**
 *
 */
function saveUserProfile()
{
    // console.log('*** saveUserProfile');

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_USER_PROFILE_UPDATE,
        'user',
        'userProfileUpdate',
        RETURN_BOOLEAN
    );

    return xhrCall(
        document,
        $("#formUserProfileUpdate").serialize(),
        xhrPath,
        localLoader,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.actionSave').addClass('saved');
        }
        localLoader.hide();
    });
}

/**
 *
 */
function saveUserOrganization()
{
    // console.log('*** saveUserOrganization');

    var localLoader = $('.actionSave').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_USER_ORGA_UPDATE,
        'user',
        'orgaProfileUpdate',
        RETURN_BOOLEAN,
        'POST'
    );

    return xhrCall(
        document,
        $("#formUserOrganizationUpdate").serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.actionSave').addClass('saved');
        }
        localLoader.hide();
    });
}

