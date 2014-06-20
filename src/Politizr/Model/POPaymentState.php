<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentState;

class POPaymentState extends BasePOPaymentState
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const STATE_PROCESS = 1;
	const STATE_WAITING = 2;
	const STATE_DONE = 3;
	const STATE_REFUSED = 4;
	const STATE_CANCELED = 5;
	// ************************************************************************************ //


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
