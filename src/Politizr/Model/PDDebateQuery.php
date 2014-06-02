<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebateQuery;

use Geocoder\Result\Geocoded;

class PDDebateQuery extends BasePDDebateQuery
{

	/**
	 *	Filtre les objets par popularité
	 *	TODO requête "populaire" à préciser et à affiner
	 *
	 * @param 	$limit 	integer
	 *
	 * @return  Query
	 */
	public function popularity($limit = 10) {
		return PDDebateQuery::create()->filterByOnline(true)->filterByPublished(true)->orderByNotePos()->orderByNoteNeg(\Criteria::DESC)->setLimit($limit);
	}

	/**
	 *	Filtre les objets par géolocalisation
	 *  TODO requête géoloc / tags
	 *
	 * @param 	$geocoded 	Geocoder\Result\Geocoded
	 */
	public function geolocalized(Geocoded $geocoded, $limit = 10) {
		return PDDebateQuery::create()->filterByOnline(true)->filterByPublished(true)->setLimit($limit);
	}
}
