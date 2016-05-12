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

    var targetElement = $(this).parent();
    var paymentTypeId = $(this).attr('paymentTypeId');

    return validatePayment(targetElement, paymentTypeId);
});
