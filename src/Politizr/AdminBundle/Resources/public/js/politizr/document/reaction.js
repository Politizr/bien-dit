$(function() {
});

$("body").on("change", "#new_adminbundle_pdreaction_p_c_topic", function(e) {
    console.log('*** click change debate');

    var select = $('#new_adminbundle_pdreaction_p_d_debate');
    $(select).html('<option>Chargement en cours...</option>');
    $(select).selectpicker('refresh');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_REACTION_CREATE_FORM_INIT,
        'admin',
        'updateFormDebatesByTopic',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'topicId': $(this).val() },
        xhrPath,
        'POST'
    ).done(function(data) {
        console.log(data);
        if (data['success']) {
            $(select).html(data['p_d_debate']);
            $(select).selectpicker('refresh');
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['errors']);
            $('#infoBoxHolder .boxError').show();
        }
        $('#ajaxGlobalLoader').hide();
    });
});

$("body").on("change", "#new_adminbundle_pdreaction_p_d_debate", function(e) {
    console.log('*** click change debate');

    var select = $('#new_adminbundle_pdreaction_parent_reaction');
    $(select).html('<option>Chargement en cours...</option>');
    $(select).selectpicker('refresh');

    var xhrPath = getXhrPath(
        ADMIN_ROUTE_REACTION_CREATE_FORM_INIT,
        'admin',
        'updateFormReactionsByDebate',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'debateId': $(this).val() },
        xhrPath,
        'POST'
    ).done(function(data) {
        console.log(data);
        if (data['success']) {
            $(select).html(data['parent_reaction']);
            $(select).selectpicker('refresh');
        } else {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['errors']);
            $('#infoBoxHolder .boxError').show();
        }
        $('#ajaxGlobalLoader').hide();
    });
});
