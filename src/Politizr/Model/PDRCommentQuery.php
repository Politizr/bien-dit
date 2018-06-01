<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDRCommentQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PDRCommentQuery extends BasePDRCommentQuery
{
    /* ######################################################################################################## */
    /*                                               AGGREGATION                                                */
    /* ######################################################################################################## */

    /**
     *
     * @return PDDCommentQuery
     */
    public function online()
    {
        return $this
            ->filterByOnline(true)
            ->filterByModerated(false)
            ->_or()
            ->filterByModerated(null);
    }

    /**
     *
     * @return PDDCommentQuery
     */
    public function offline()
    {
        return $this
            ->filterByOnline(false)
            ->_or()
            ->filterByModerated(true);
    }

    /**
     * Best note pos
     *
     * @return PDDCommentQuery
     */
    public function bestNote()
    {
        return $this->orderByNotePos('desc');
    }

    /**
     * Last published
     *
     * @return PDDCommentQuery
     */
    public function last()
    {
        return $this->orderByPublishedAt('desc');
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
     * @param boolean $paragraphNo
     */
    public function filterIfParagraphNo($paragraphNo = null)
    {
        return $this
            ->_if($paragraphNo !== null)
                ->filterByParagraphNo($paragraphNo)
            ->_endif();
    }

    /**
     *
     * @param boolean $onlyElected
     */
    public function filterIfOnlyElected($onlyElected = null)
    {
        return $this
            ->_if(null !== $onlyElected)
                ->usePUserQuery()
                    ->filterByQualified(true)
                ->endUse()
            ->_endif();
    }
}
