<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOOrderState;

class POOrderState extends BasePOOrderState
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const STATE_OPEN = 1;
	const STATE_HANDLED = 2;
	const STATE_CANCEL = 3;
	// ************************************************************************************ //


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
