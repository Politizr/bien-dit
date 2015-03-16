<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentState;

class POPaymentState extends BasePOPaymentState
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
    const PROCESSING = 1;
    const WAITING = 2;
    const DONE = 3;
    const REFUSED = 4;
    const CANCELED = 5;
    // ************************************************************************************ //


    /**
     *
     */
    public function __toString() {
        return $this->getTitle();
    }
}
