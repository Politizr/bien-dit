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
	 *	Derniers commentaires publiés
	 *
	 */
	public function last($limit = 10) {
		return $this->orderByPublishedAt(\Criteria::DESC)->setLimit($limit);
	}
}
