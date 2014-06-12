<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

use Politizr\Model\PUser;

class PUserQuery extends BasePUserQuery
{


    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByStatus(PUser::STATUS_ACTIV);
    }

	/**
	 *	Filtre à appliquer aux objets retournés en page d'accueil
	 *  TODO qu'est ce qu'un auteur "populaire"? + requête
	 *
	 */
	public function popularity($limit = 10) {
		return $this->filterByStatus(PUser::STATUS_ACTIV)->filterByType(PUser::TYPE_QUALIFIE)->setLimit($limit);
	}
}
