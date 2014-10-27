<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadge;

class PRBadge extends BasePRBadge
{
	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

 	/**
	 * Override to manage accented characters
	 * @return string
	 */
	protected function createRawSlug()
	{
		$toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($this->getTitle());
		$slug = $this->cleanupSlugPart($toSlug);
		return $slug;
	}
	
	/******************************************************************************/

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function countPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::countPuReputationRbPUsers($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPuReputationRbPUsers($con, $doQuery);
	}

}
