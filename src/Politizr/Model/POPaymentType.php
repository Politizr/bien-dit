<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentType;

class POPaymentType extends BasePOPaymentType
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
    const BANK_TRANSFER = 1;
    const CREDIT_CARD = 2;
    const CHECK = 3;
    const PAYPAL = 4;
    // ************************************************************************************ //

    /**
     *
     */
    public function __toString() {
        return $this->getTitle();
    }
}