/**
 * Validate payment
 *
 * @param targetElement
 */
function validatePayment(targetElement, paymentTypeId) {
    console.log('*** validatePayment');
    console.log(targetElement);
    console.log(paymentTypeId);

    var xhrPath = getXhrPath(
        ROUTE_SECURITY_PAYMENT_PROCESS,
        'security',
        'paymentProcess',
        RETURN_HTML
    );

    return xhrCall(
        document,
        { 'paymentTypeId': paymentTypeId },
        xhrPath
    ).done(function(data) {
        if (data['error']) {
            $('#ajaxGlobalLoader').hide();
            $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
            $('#infoBoxHolder .boxError').show();
        } else {
            if (data['redirect']) {
                window.location = data['redirectUrl'];
            } else {
                $('#ajaxGlobalLoader').hide();
                targetElement.html(data['htmlForm']);
            }
        }
    });
}
