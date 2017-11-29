// on document ready
$(function() {
    $(".paymentDetail").hide();
});

// payment choice
$("body").on("click", "[action='paymentChoice']", function() {
    // console.log('*** click paymentChoice');

    $(".paymentDetail").hide();
    $(this).siblings(".paymentDetail").show();
});

// payment process
$("body").on("click", "[action='paymentProcess']", function(e) {
    // console.log('*** paymentProcess');

    var targetElement = $(this).parent();
    var paymentTypeId = $(this).attr('paymentTypeId');

    return validatePayment(targetElement, paymentTypeId);
});
