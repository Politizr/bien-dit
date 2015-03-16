<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocumentQuery;

class PDocumentQuery extends BasePDocumentQuery
{
    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
        return $this->filterByOnline(true)->filterByPublished(true);
    }

}
