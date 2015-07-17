<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDReactionQuery;

/**
 * Reaction query
 *
 * @author Lionel Bouzonville
 */
class PDReactionQuery extends BasePDReactionQuery
{
    /* ######################################################################################################## */
    /*                                               AGGREGATION                                                */
    /* ######################################################################################################## */

    /**
     *
     */
    public function online()
    {
        return $this->filterByOnline(true)->filterByPublished(true);
    }

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

    /**
     *
     * @param boolean $published
     */
    public function filterIfPublished($published = null)
    {
        return $this
            ->_if(null !== $published)
                ->filterByPublished($published)
            ->_endif();
    }

    /**
     *
     * @param boolean $treeLevel
     */
    public function filterIfTreeLevel($treeLevel = null)
    {
        return $this
            ->_if(null !== $treeLevel)
                ->filterByTreeLevel($treeLevel)
            ->_endif();
    }
}
