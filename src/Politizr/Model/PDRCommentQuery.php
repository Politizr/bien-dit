<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDRCommentQuery;

class PDRCommentQuery extends BasePDRCommentQuery
{

    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true);
    }

}
