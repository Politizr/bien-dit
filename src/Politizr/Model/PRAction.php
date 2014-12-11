<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRAction;

/**
 *	Gestion de la réputation.
 *
 * 	@author 	Lionel Bouzonville
 */
class PRAction extends BasePRAction
{
	// ******************************************************** //
	//					 Constantes des actions 				//
	// ******************************************************** //

	// ************ PUBLICATIONS ******* //
    const ID_D_DEBATE_PUBLISH = 1;
    const ID_D_REACTION_PUBLISH = 2;
    const ID_D_COMMENT_PUBLISH = 3;
    
    const ID_D_TARGET_DEBATE_REACTION_PUBLISH = 4;
    const ID_D_TARGET_REACTION_REACTION_PUBLISH = 5;

    CONST ID_D_TARGET_DEBATE_COMMENT_PUBLISH = 6;
    CONST ID_D_TARGET_REACTION_COMMENT_PUBLISH = 7;


	// ************ NOTATIONS ******* //
    const ID_D_AUTHOR_DEBATE_NOTE_POS = 8;
    const ID_D_AUTHOR_DEBATE_NOTE_NEG = 9;

    const ID_D_AUTHOR_REACTION_NOTE_POS = 10;
    const ID_D_AUTHOR_REACTION_NOTE_NEG = 11;

    const ID_D_AUTHOR_COMMENT_NOTE_POS = 12;
    const ID_D_AUTHOR_COMMENT_NOTE_NEG = 13;

    const ID_D_TARGET_DEBATE_NOTE_POS = 14;
    const ID_D_TARGET_DEBATE_NOTE_NEG = 15;

    const ID_D_TARGET_REACTION_NOTE_POS = 16;
    const ID_D_TARGET_REACTION_NOTE_NEG = 17;

    const ID_D_TARGET_COMMENT_NOTE_POS = 18;
    const ID_D_TARGET_COMMENT_NOTE_NEG = 19;


	// ************ FOLLOWING ******* //
    const ID_D_AUTHOR_DEBATE_FOLLOW = 20;
    const ID_D_AUTHOR_DEBATE_UNFOLLOW = 21;

    const ID_U_AUTHOR_USER_FOLLOW = 22;
    const ID_U_AUTHOR_USER_UNFOLLOW = 23;

    const ID_D_TARGET_DEBATE_FOLLOW = 24;
    const ID_D_TARGET_DEBATE_UNFOLLOW = 25;

    const ID_U_TARGET_USER_FOLLOW = 26;
    const ID_U_TARGET_USER_UNFOLLOW = 27;

	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

	/******************************************************************************/

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function countPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::countPuReputationRaPUsers($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPuReputationRaPUsers($con, $doQuery);
	}

}
