<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentType;

class POPaymentType extends BasePOPaymentType
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const TYPE_BANK_TRANSFER = 1;
	const TYPE_CREDIT_CARD = 2;
	const TYPE_CHECK = 3;
	const TYPE_PAYPAL = 4;
	// ************************************************************************************ //

	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}