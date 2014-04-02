<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOSubscription;

class POSubscription extends BasePOSubscription
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	// ************************************************************************************ //


	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
