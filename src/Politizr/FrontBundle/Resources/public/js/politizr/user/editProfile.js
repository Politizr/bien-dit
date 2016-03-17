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
        localLoader
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
        RETURN_BOOLEAN
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

/**
 *
 */
function createUserMandate()
{
    // console.log('*** createUserMandate');

    var localLoader = $('#newMandate').find('.ajaxLoader').first();

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_CREATE,
        'user',
        'mandateProfileCreate',
        RETURN_HTML
    );

    return xhrCall(
        document,
        $("#formMandateCreate").serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#newMandate').html(data['newMandate']);
            $('#editMandates').html(data['editMandates']);
        }
        localLoader.hide();
    });
}

/**
 *
 */
function saveUserMandate(form, localLoader)
{
    // console.log('*** saveUserMandate');
    // console.log(form);
    // console.log(localLoader);

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_UPDATE,
        'user',
        'mandateProfileUpdate',
        RETURN_HTML
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#editMandates').html(data['editMandates']);
        }
        localLoader.hide();
    });
}

/**
 *
 */
function deleteUserMandate(uuid, localLoader)
{
    // console.log('*** deleteUserMandate');
    // console.log(uuid);
    // console.log(localLoader);

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_DELETE,
        'user',
        'mandateProfileDelete',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        localLoader
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#editMandates').html(data['editMandates']);
        }
        localLoader.hide();
    });
}

