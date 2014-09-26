<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebateQuery;

use Geocoder\Result\Geocoded;

class PDDebateQuery extends BasePDDebateQuery
{


    // *****************************    SURCHARGE / DOCUMENT    ************************* //
    
	/**
	 *
	 */
	public function filterByOnline($bool = true) {
		return $this->join('PDocument', 'left join')
  					->where('PDocument.Online = ?', $bool);

	}

	/**
	 *
	 */
	public function filterByPublished($bool = true) {
		return $this->join('PDocument', 'left join')
  					->where('PDocument.Published = ?', $bool);

	}



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
	 *		> + de followers?
	 *
	 * @param 	$limit 	integer
	 *
	 * @return  Query
	 */
	public function popularity($limit = 10) {
		return $this->setLimit($limit);

		// followers
		return $this->joinPuFollowDdPDDebate('PUFollowDD', \Criteria::LEFT_JOIN)
				->withColumn('COUNT(PUFollowDD.PUserId)', 'NbFollowers')
				->groupBy('Id')
				->setLimit($limit)
				->orderBy('NbFollowers', \Criteria::DESC)
				;

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
