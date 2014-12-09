<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUStatus;

class PUStatus extends BasePUStatus
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
    const ACTIVED = 1;
	const VALIDATION_PROCESS = 2;
	const ARCHIVED = 3;


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

}
