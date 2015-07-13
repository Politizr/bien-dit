<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDCommentQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PDDCommentQuery extends BasePDDCommentQuery
{
    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     *
     */
    public function online()
    {
        return $this->filterByOnline(true);
    }

    /**
     *    Ordonne les objets par meilleur note
     *
     *     @return  Query
     */
    public function bestNote()
    {
        return $this->orderByNotePos(\Criteria::DESC);
    }

    /**
     *    Derniers commentaires publiés
     *
     */
    public function last()
    {
        return $this->orderByPublishedAt(\Criteria::DESC);
    }
}
