<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDReactionQuery;

class PDReactionQuery extends BasePDReactionQuery
{

    // *****************************    SURCHARGE / DOCUMENT    ************************* //
    
   // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByPublished(true);
    }

}
