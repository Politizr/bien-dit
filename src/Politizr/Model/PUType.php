<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUType;

class PUType extends BasePUType
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const TYPE_CITOYEN = 1;
	const TYPE_QUALIFIE = 2;


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
