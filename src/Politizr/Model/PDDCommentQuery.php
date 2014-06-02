<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDCommentQuery;

class PDDCommentQuery extends BasePDDCommentQuery
{

	/**
	 *	Filtre à appliquer aux objets retournés en page d'accueil
	 *	TODO requête "populaire" à préciser et à affiner
	 *
	 */
	public function last($limit = 10) {
		return PDDCommentQuery::create()->filterByOnline(true)->orderByPublishedAt(\Criteria::DESC)->setLimit($limit);
	}
}
