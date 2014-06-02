<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

use Politizr\Model\PUser;

class PUserQuery extends BasePUserQuery
{

	/**
	 *	Filtre à appliquer aux objets retournés en page d'accueil
	 *  TODO qu'est ce qu'un auteur "populaire"? + requête
	 *
	 */
	public function popularity($limit = 10) {
		return PUserQuery::create()->filterByOnline(true)->filterByStatus(PUser::STATUS_ACTIV)->filterByType(PUser::TYPE_QUALIFIE)->setLimit($limit);
	}
}
