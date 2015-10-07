// on document ready
$(function() {
    $(".payment-detail").hide();
});

// payment choice
$("body").on("click", "[action='payment-choice']", function() {
    console.log('*** click radio-choice');

    $(".payment-detail").hide();
    $(this).siblings(".payment-detail").show();
});

// payment process
$("body").on("click", "[action='payment-process']", function(e) {
    console.log('*** payment-process');

    var xhrPath = getXhrPath(
        ROUTE_SECURITY_PAYMENT_PROCESS,
        'security',
        'paymentProcess',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        context: this,
        data: { 'pOPaymentTypeId': $(this).attr('pOPaymentTypeId') },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#ajaxGlobalLoader').hide();
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                if (data['redirect']) {
                    window.location = data['redirectUrl'];
                } else {
                    $('#ajaxGlobalLoader').hide();
                    $(this).parent().html(data['htmlForm']);
                }
            }
        }
    });
});
