// beta

// init select2 & hide cities
$(function() {
    // console.log('*** init edit.js');

    $departmentUuid = $('.department_choice').val();
    // console.log($departmentUuid);
    if ($departmentUuid == '') {
        $('.control-group-city').hide();
    }

    // select2 init depending of context
    var select2Options = initContextSelect2Options();
    // console.log(select2Options);
    $('select.select2_choice').select2(select2Options);
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
 * @param localLoader
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
            // console.log(select2Options);
            $('select.city_choice').select2(select2Options);
            $('.control-group-city').show();
        }
    });
}
