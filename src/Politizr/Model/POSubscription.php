<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOSubscription;

class POSubscription extends BasePOSubscription
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //

    // ************************************************************************************ //
    
    /**
     *
     */
    public function __toString() {
        return $this->getTitle();
    }

    // ************************************************************************************ //
    //                                  METHODES PUBLIQUES
    // ************************************************************************************ //

    /**
     *    Formatte le titre + le prix
     *
     * @return string
     */
    public function getTitleAndPrice() {
        return $this->getTitle() . ' - ' . number_format($this->getPrice(), 2, ',', ' ') .'€';
    }

}
