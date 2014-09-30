<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUFollowTQuery;

/**
 *
 *
 * 	@author Lionel Bouzonville
 */
class PUFollowTQuery extends BasePUFollowTQuery
{
	/**
	 *	Création d'une nouvelle entrée PUFollowT tag / user.
	 *
	 *	@param 	$pUserId 	ID user
	 *  @param  $pTagId 	ID tag
	 *
	 *  @return 	integer 	ID de l'entrée créé, ou false si l'entrée n'a pas pu être créée
	 */
	public function addElement($pUserId = null, $pTagId = null) {
        $pUFollowT = PUFollowTQuery::create()->filterByPUserId($pUserId)->filterByPTagId($pTagId)->findOne();
        if (!$pUFollowT && $pUserId != null && $pTagId != null) {
            $pUFollowT = new PUFollowT();

            $pUFollowT->setPUserId($pUserId);
            $pUFollowT->setPTagId($pTagId);

            $pUFollowT->save();
        } else {
        	return false;
        }

        return $pUFollowT->getId();
    }


	/**
	 *	Suppression d'une entrée PUFollowT tag / user.
	 *
	 *	@param 	$pUserId 	ID user
	 *  @param  $pTagId 	ID tag
	 *
	 *  @return 	boolean 	Vrai si l'entrée a pu être supprimée, faux sinon	
	 */
	public function deleteElement($pUserId = null, $pTagId = null) {
        $pUFollowT = PUFollowTQuery::create()->filterByPUserId($pUserId)->filterByPTagId($pTagId)->findOne();
        if (!$pUFollowT) {
            return false;
        } else {
            $pUFollowT->delete();
            return true;
        }
    }

}
