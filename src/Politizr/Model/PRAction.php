<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRAction;

class PRAction extends BasePRAction
{
	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

	/******************************************************************************/

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function countPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::countPuReputationRaPUsers($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPuReputationRaPUsers($con, $doQuery);
	}

}
