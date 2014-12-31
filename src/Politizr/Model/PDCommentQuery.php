<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDCommentQuery;

class PDCommentQuery extends BasePDCommentQuery
{

    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true);
    }

	/**
	 *	Ordonne les objets par meilleur note
	 *
	 * 	@return  Query
	 */
	public function bestNote() {
		return $this->orderByNotePos(\Criteria::DESC);
	}

	/**
	 *	Derniers commentaires publiés
	 *
	 */
	public function last() {
		return $this->orderByPublishedAt(\Criteria::DESC);
	}
}
