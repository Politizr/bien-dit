<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

class PUserQuery extends BasePUserQuery
{


    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByPUStatusId(PUStatus::ACTIVED);
    }

	/**
	 *	Ordonne les objets par nombre de followers
	 *
	 */
	public function mostFollowed() {
		return $this->joinPUFollowURelatedByPUserId('PUFollowU', \Criteria::LEFT_JOIN)
				->withColumn('COUNT(PUFollowU.PUserId)', 'NbFollowers')
				->groupBy('Id')
				->orderBy('NbFollowers', \Criteria::DESC)
				;
	}


	/**
	 *	Derniers users créés
	 *
	 */
	public function last() {
		return $this->orderByCreatedAt(\Criteria::DESC);
	}

}
