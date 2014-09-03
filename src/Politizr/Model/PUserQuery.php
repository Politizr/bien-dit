<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PUStatus;

class PUserQuery extends BasePUserQuery
{


    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByPUStatusId(PUStatus::STATUS_ACTIV);
    }

	/**
	 *	Filtre à appliquer aux objets retournés en page d'accueil
	 *  TODO qu'est ce qu'un auteur "populaire"? + requête
	 *
	 */
	public function popularity($limit = 10) {
		return $this->filterByPUStatusId(PUStatus::STATUS_ACTIV)->filterByPUTypeId(PUType::TYPE_QUALIFIE)->setLimit($limit);
	}
}
