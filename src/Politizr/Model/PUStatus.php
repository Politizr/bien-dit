<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUStatus;

class PUStatus extends BasePUStatus
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
    const STATUS_ACTIV = 1;
	const STATUS_VALIDATION_PROCESS = 2;
	const STATUS_ARCHIVE = 3;


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

}
