<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeMetal;

class PRBadgeMetal extends BasePRBadgeMetal
{

    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
    const GOLD = 1;
    const SILVER = 2;
    const BRONZE = 3;

    // *****************************  OBJET / STRING  ****************** //

    /**
     *
     */
    public function __toString() {
        return $this->getTitle();
    }


    // *****************************  BADGES  ****************** //

    /**
     *
     */
    public function getBadges($online = true) {
        $query = PRBadgeQuery::create()
                ->_if($online)
                    ->filterByOnline($online)
                ->_endif()
                ->orderByTitle();

        return parent::getPRBadges($query);
    }
}
