<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeType;

class PRBadgeType extends BasePRBadgeType
{

	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const TYPE_DEBATE = 1;
	const TYPE_COMMENT = 2;
	const TYPE_PARTICIPATION = 3;
	const TYPE_MODERATION = 4;
	const TYPE_OTHER = 5;


	// *****************************  OBJET / STRING  ****************** //

	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
