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
	 *	Filtre à appliquer aux objets retournés en page d'accueil
	 *	TODO requête "populaire" à préciser et à affiner
	 *
	 */
	public function last($limit = 10) {
		return $this->filterByOnline(true)->orderByPublishedAt(\Criteria::DESC)->setLimit($limit);
	}
}
