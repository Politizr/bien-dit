<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTagQuery;

/**
 * Tag query
 *
 * @author Lionel Bouzonville
 */
class PTagQuery extends BasePTagQuery
{

    /* ######################################################################################################## */
    /*                                              FILTERBY IF                                                 */
    /* ######################################################################################################## */

    /**
     *
     * @param boolean $online
     */
    public function filterIfOnline($online = null)
    {
        return $this
            ->_if(null !== $online)
                ->filterByOnline($online)
            ->_endif();
    }
}
