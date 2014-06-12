<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebateQuery;

use Geocoder\Result\Geocoded;

class PDDebateQuery extends BasePDDebateQuery
{



    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByPublished(true);
    }

	/**
	 *	Filtre les objets par popularité
	 *	TODO requête "populaire" à préciser et à affiner
	 *
	 * @param 	$limit 	integer
	 *
	 * @return  Query
	 */
	public function popularity($limit = 10) {
		return $this->setLimit($limit);
	}

	/**
	 *	Filtre les objets par géolocalisation
	 *  TODO requête géoloc / tags
	 *
	 * @param 	$geocoded 	Geocoder\Result\Geocoded
	 */
	public function geolocalized(Geocoded $geocoded, $limit = 10) {
		return $this->setLimit($limit);
	}


}
