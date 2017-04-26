// beta
$(function() {
    // console.log('*** init edit.js');

    if ($('.modalPublishContent').length) {
        initPublishContext();
    } else {
        initUserContext();
    }
});

// department choice event
$("body").on("change", ".department_choice", function(e) {
    // console.log('*** click change department');

    var context = $('#main');
    var departmentUuid = $(this).val();

    if (departmentUuid == '') {
        $('.control-group-city').hide();
        $('.city_choice').html();

        return;
    }

    return initCities(context, departmentUuid);
});

// out of france checkbox
$("body").on("change", ".out_of_france", function(e) {
    // console.log('*** click out of france');

    var context = $('#main');

    if (this.checked) {
        // console.log('checked');
        $('.control-group-department').hide();
        $('.control-group-city').hide();

        return initOutOfFranceDepartments(context);
    } else {
        // console.log('unchecked');
        $('.control-group-circonscription').hide();
        $('.control-group-department').show();
        $departmentUuid = $('.department_choice').val();
        // console.log($departmentUuid);
        if ($departmentUuid != '') {
            $('.control-group-city').show();
        }
    }

    return;
});

/**
 * Select box initialization in publish debate/response context
 */
function initPublishContext()
{
    // console.log('*** initPublishContext');

    departmentUuid = $('.department_choice').val();
    // console.log(departmentUuid);
    if (departmentUuid == '') {
        $('.control-group-city').hide();
    }

    // select2 init depending of context
    var select2Options = initContextSelect2Options();
    $('select.select2_choice').select2(select2Options);
}

/**
 * Select box initialization in publish user edit context
 */
function initUserContext()
{
    // console.log('*** initUserContext');
    
    if ($('.out_of_france').is(':checked')) {
        // console.log('outOfFrance checked');
        $('.control-group-department').hide();
        $('.control-group-city').hide();
        departmentUuid = $('.circonscription_choice').val();
        // console.log(departmentUuid);
    } else {
        // console.log('outOfFrance unchecked');
        $('.control-group-circonscription').hide();
        departmentUuid = $('.department_choice').val();
        // console.log(departmentUuid);
        if (departmentUuid == '') {
            $('.control-group-city').hide();
        }
    }

    // select2 init depending of context
    var select2Options = initContextSelect2Options();
    $('select.select2_choice').select2(select2Options);
}

/**
 * Init select2options: important to manage "dropdownParent" in case of use in modal
 *
 * @return array
 */
function initContextSelect2Options()
{
    var select2Options = {
        language: "fr",
    };
    if ($('.modalPublishContent').length) {
        select2Options = {
            language: "fr",
            dropdownParent: $('.modalPublishContent')
        };
    }

    return select2Options;
}

/**
 * Refresh city selection by department
 *
 * @param contextZone
 * @param departmentUuid
 */
function initCities(contextZone, departmentUuid)
{
    // console.log('*** initCities');
    // console.log(departmentUuid);

    var xhrPath = getXhrPath(
        xhrRouteCity,
        'localization',
        'citiesSelectList',
        RETURN_HTML
    );

    return xhrCall(
        contextZone,
        { 'departmentUuid': departmentUuid },
        xhrPath,
        null
    ).done(function(data) {
        $('#ajaxGlobalLoader').hide();
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.city_choice').html(data['html']);
            // select2 init depending of context
            var select2Options = initContextSelect2Options();
            $('select.city_choice').select2(select2Options);
            $('.control-group-city').show();
        }
    });
}

/**
 * Refresh out of france departments selection
 *
 * @param contextZone
 */
function initOutOfFranceDepartments(contextZone)
{
    // console.log('*** initOutOfFranceDepartments');

    var xhrPath = getXhrPath(
        xhrRouteCity,
        'localization',
        'circonscriptionsSelectList',
        RETURN_HTML
    );

    return xhrCall(
        contextZone,
        null,
        xhrPath,
        null
    ).done(function(data) {
        $('#ajaxGlobalLoader').hide();
        if (data['error']) {
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            $('.circonscription_choice').html(data['html']);
            // select2 init depending of context
            var select2Options = initContextSelect2Options();
            $('select.circonscription_choice').select2(select2Options);
            $('.control-group-circonscription').show();
        }
    });
}
