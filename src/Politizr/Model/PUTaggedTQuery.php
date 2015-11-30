<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUTaggedTQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PUTaggedTQuery extends BasePUTaggedTQuery
{
    /* ######################################################################################################## */
    /*                                              FILTERBY IF                                                 */
    /* ######################################################################################################## */

    /**
     *
     * @param boolean $hidden
     * @return PTagQuery
     */
    public function filterIfHidden($hidden = null)
    {
        return $this
            ->_if(null !== $hidden)
                ->filterByHidden($hidden)
            ->_endif();
    }
}
