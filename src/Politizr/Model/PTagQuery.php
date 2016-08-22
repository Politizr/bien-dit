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
     * @return PTagQuery
     */
    public function filterIfOnline($online = null)
    {
        return $this
            ->_if(null !== $online)
                ->filterByOnline($online)
            ->_endif();
    }

    /**
     *
     * @param int|array $typeId
     * @return PTagQuery
     */
    public function filterIfTypeId($typeId = null)
    {
        return $this
            ->_if(null !== $typeId)
                ->filterByPTTagTypeId($typeId)
            ->_endif();
    }
}
