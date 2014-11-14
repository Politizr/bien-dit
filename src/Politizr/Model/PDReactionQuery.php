<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDReactionQuery;

class PDReactionQuery extends BasePDReactionQuery
{


	/**
	 *	Renvoit le noeud root, ou le créé au préalable s'il n'existe pas.
	 *
	 * 	@return PDReaction
	 */
	public function findOrCreateRoot($debateId) {
		$rootNode = $this->findRoot($debateId);

		if (!$rootNode) {
	        $rootNode = new PDReaction();

	        $rootNode->setPDDebateId($debateId);
	        $rootNode->setTitle('ROOT NODE');
	        $rootNode->setOnline(false);
	        $rootNode->setPublished(false);

	        $rootNode->makeRoot();
	        $rootNode->save();
		}

		return $rootNode;
	}


   // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     */
    public function online() {
    	return $this->filterByOnline(true)->filterByPublished(true);
    }

}
