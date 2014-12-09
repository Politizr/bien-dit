<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOOrderState;

class POOrderState extends BasePOOrderState
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const CREATED = 1;
	const WAITING = 2;
	const OPEN = 3;
	const HANDLED = 4;
	const CANCELED = 5;
	// ************************************************************************************ //


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
