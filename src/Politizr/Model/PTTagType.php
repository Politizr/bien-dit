<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTTagType;

class PTTagType extends BasePTTagType
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const TYPE_MANDAT = 1;
	const TYPE_GEO = 2;
	const TYPE_THEME = 3;

	// ************************************************************************************ //

	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
	public function getBlockTags() {
	}
}
