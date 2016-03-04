<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDCommentQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PDDCommentQuery extends BasePDDCommentQuery
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
        return $this->filterByOnline(true);
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
}
