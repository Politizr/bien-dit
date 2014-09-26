<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDReactionQuery;

class PDReactionQuery extends BasePDReactionQuery
{

    // *****************************    SURCHARGE / DOCUMENT    ************************* //
    
	/**
	 *
	 */
	public function filterByOnline($bool = true) {
		return $this->join('PDocument')
  					->where('PDocument.Online = ?', $bool);

	}

	/**
	 *
	 */
	public function filterByPublished($bool = true) {
		return $this->join('PDocument')
  					->where('PDocument.Published = ?', $bool);

	}

    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByPublished(true);
    }

}
