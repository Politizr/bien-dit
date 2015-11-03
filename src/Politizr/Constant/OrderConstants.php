<?php

namespace Politizr\Constant;

/**
 * Order constants
 *
 * @author Lionel Bouzonville
 */
class OrderConstants
{
    // ************************************************************************************ //
    //                                        ORDER STATES
    // ************************************************************************************ //
    const ORDER_CREATED = 1;
    const ORDER_WAITING = 2;
    const ORDER_OPEN = 3;
    const ORDER_HANDLED = 4;
    const ORDER_CANCELED = 5;

    // ************************************************************************************ //
    //                                        PAYMENT STATES
    // ************************************************************************************ //
    const PAYMENT_PROCESSING = 1;
    const PAYMENT_WAITING = 2;
    const PAYMENT_DONE = 3;
    const PAYMENT_REFUSED = 4;
    const PAYMENT_CANCELED = 5;

    // ************************************************************************************ //
    //                                        PAYMENT TYPES
    // ************************************************************************************ //
    const PAYMENT_TYPE_BANK_TRANSFER = 1;
    const PAYMENT_TYPE_CREDIT_CARD = 2;
    const PAYMENT_TYPE_CHECK = 3;
    const PAYMENT_TYPE_PAYPAL = 4;
}
