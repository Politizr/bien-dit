// JCF forms styling
$(function() {
    jcf.replaceAll();
});

/**
 * Auto save
 * Event = keyup + 2sec
 * http://stackoverflow.com/questions/9966394/can-i-delay-the-keyup-event-for-jquery
 */
var autosave = $('.editable.subtitle, .editable.biography').on('keyup', delayRequest);
function dataRequest() {
    $('[action="userProfileUpdate"]').trigger('click', 'silence');
}
function delayRequest(ev) {
    if(delayRequest.timeout) {
        clearTimeout(delayRequest.timeout);
    }

    var target = this;

    delayRequest.timeout = setTimeout(function() {
        dataRequest.call(target, ev);
    }, 2000); // 2s
}

/**
 * Auto save
 * Event = mouseup
 */
$('.editable.subtitle, .editable.biography').on('mouseup', function() {
    // console.log('mouseup debate description');
    $('[action="userProfileUpdate"]').trigger('click', 'silence');
});


// Update user
$("body").on("click", "[action='userProfileUpdate']", function(e, mode) {
    // console.log('*** click user profile update');
    // console.log(mode);

    var subtitle = subtitleEditor.serialize();
    var biography = biographyEditor.serialize();

    // console.log(subtitle['element-0']['value']);
    // console.log(biography['element-0']['value']);

    $('#user_subtitle').val(subtitle['element-0']['value']);
    $('#user_biography').val(biography['element-0']['value']);
 
    var xhrPath = getXhrPath(
        ROUTE_USER_PROFILE_UPDATE,
        'user',
        'userProfileUpdate',
        RETURN_BOOLEAN
        );

    if (mode === 'silence') {
        // sauvegarde silencieuse
        $.ajax({type: 'POST', url : xhrPath, data: $("#formUserProfileUpdate").serialize() });
    } else {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url : xhrPath,
            data: $("#formUserProfileUpdate").serialize(),
            beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
            statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
            error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
            success: function(data) {
                $('#ajaxGlobalLoader').hide();
                if (data['error']) {
                    $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                    $('#infoBoxHolder .boxError').show();
                } else {
                    $('#infoBoxHolder .boxSuccess .notifBoxText').html('Objet bien enregistré!');
                    $('#infoBoxHolder .boxSuccess').show();
                }
            }
        });
    }

    return false;
});


// Update current organization
$("body").on("change", "select[action='organizationUpdate']", function(e) {
    // console.log('*** change select organization');

    var xhrPath = getXhrPath(
        ROUTE_USER_ORGA_UPDATE,
        'user',
        'orgaProfileUpdate',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : xhrPath,
        data: $("#formUserOrganizationUpdate").serialize(),
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            $('#ajaxGlobalLoader').hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Organisation bien enregistrée.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});

// MAJ des affinités politiques
// @todo dead code / relancer rossier sur le sujet
$("body").on("click", "[action='affinities-update']", function(e) {
    // console.log('*** click affinities-update');

    var xhrPath = getXhrPath(
        ROUTE_USER_AFFINITIES_UPDATE,
        'user',
        'affinitiesProfile',
        RETURN_BOOLEAN
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $('#form-affinities-update').serialize(),
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
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Affinités mises à jour.');
                $('#infoBoxHolder .boxSuccess').show();
            }
        }
    });
});

// Mandate creation
$("body").on("click", "[action='mandateCreate']", function(e) {
    // console.log('*** click mandateCreate');

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_CREATE,
        'user',
        'mandateProfileCreate',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $("#formMandateCreate").serialize(),
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
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Mandat bien enregistré.');
                $('#infoBoxHolder .boxSuccess').show();

                $('#newMandate').html(data['newMandate']);
                $('#myMandates').html(data['editMandates']);
                
                // JCF forms styling
                jcf.replaceAll();
            }
        }
    });
});

// Mandate update
$("body").on("click", "[action='mandateUpdate']", function(e) {
    // console.log('*** click mandateUpdate');

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_UPDATE,
        'user',
        'mandateProfileUpdate',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: $(this).closest('#formMandateUpdate').serialize(),
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
                $('#infoBoxHolder .boxSuccess .notifBoxText').html('Mandat mis à jour.');
                $('#infoBoxHolder .boxSuccess').show();

                $('#myMandates').html(data['editMandates']);
                
                // JCF forms styling
                jcf.replaceAll();
            }
        }
    });
});

// Mandate deletion
$("body").on("click", "[action='mandateDelete']", function(e) {
    // console.log('*** click mandateDelete');

    var xhrPath = getXhrPath(
        ROUTE_USER_MANDATE_DELETE,
        'user',
        'mandateProfileDelete',
        RETURN_HTML
        );
    var subjectId = $(this).attr('subjectId');
    // console.log('subjectId = ' + subjectId);

    var confirmMsg = "Êtes-vous sûr de vouloir supprimer ce mandat?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            $.ajax({
                type: 'POST',
                url: xhrPath,
                data: { 'id': subjectId },
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
                        $('#infoBoxHolder .boxSuccess .notifBoxText').html('Mandat supprimé.');
                        $('#infoBoxHolder .boxSuccess').show();

                        $('#myMandates').html(data['editMandates']);
                        
                        // JCF forms styling
                        jcf.replaceAll();
                    }
                }
            });
        }
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

