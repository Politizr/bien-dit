<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentState;

class POPaymentState extends BasePOPaymentState
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const STATE_PAYMENT_WAITING = 1;
	const STATE_PAYMENT_OK = 2;
	const STATE_PAYMENT_REFUSED = 3;
	// ************************************************************************************ //


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
