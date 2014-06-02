<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeType;

class PRBadgeType extends BasePRBadgeType
{
	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}
}
