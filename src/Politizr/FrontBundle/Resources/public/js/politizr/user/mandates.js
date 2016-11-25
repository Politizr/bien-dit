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
        localLoader,
        'POST'
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
        localLoader,
        'POST'
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
        localLoader,
        'POST'
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
function createAdminUserMandate(uuid)
{
    // console.log('*** createAdminUserMandate');
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_CREATE,
        'admin',
        'mandateProfileCreate',
        RETURN_HTML
    );

    return xhrCall(
        document,
        $("#formMandateCreate").serialize(),
        xhrPath,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#newMandate').html(data['newMandate']);
            $('#editMandates').html(data['editMandates']);
        }
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 *
 */
function saveAdminUserMandate(form)
{
    // console.log('*** saveAdminUserMandate');
    // console.log(form);

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_UPDATE,
        'admin',
        'mandateProfileUpdate',
        RETURN_HTML
    );

    return xhrCall(
        document,
        form.serialize(),
        xhrPath,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#editMandates').html(data['editMandates']);
        }
        $('#ajaxGlobalLoader').hide();
    });
}

/**
 *
 */
function deleteAdminUserMandate(uuid)
{
    // console.log('*** deleteUserMandate');
    // console.log(uuid);

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_DELETE,
        'admin',
        'mandateProfileDelete',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'uuid': uuid },
        xhrPath,
        'POST'
    ).done(function(data) {
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('#editMandates').html(data['editMandates']);
        }
        $('#ajaxGlobalLoader').hide();
    });
}

