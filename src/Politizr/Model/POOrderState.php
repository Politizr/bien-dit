<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOOrderState;

class POOrderState extends BasePOOrderState
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const STATE_CREATE = 1;
	const STATE_WAITING = 2;
	const STATE_OPEN = 3;
	const STATE_HANDLED = 4;
	const STATE_CANCEL = 5;
	// ************************************************************************************ //


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
