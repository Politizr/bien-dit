<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeType;

class PRBadgeType extends BasePRBadgeType
{

	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const DEBAT = 1;
	const COMMENTAIRE = 2;
	const PARTICIPATION = 3;
	const MODERATION = 4;
	const AUTRE = 5;


	// *****************************  OBJET / STRING  ****************** //

	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
