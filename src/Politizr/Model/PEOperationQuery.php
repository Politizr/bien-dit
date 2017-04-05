<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePEOperationQuery;

class PEOperationQuery extends BasePEOperationQuery
{
    /* ######################################################################################################## */
    /*                                              FILTERBY IF                                                 */
    /* ######################################################################################################## */

    /**
     *
     * @param boolean $online
     * @return PEOperationQuery
     */
    public function filterIfOnline($online = null)
    {
        return $this
            ->_if(null !== $online)
                ->filterByOnline($online)
            ->_endif();
    }

}
