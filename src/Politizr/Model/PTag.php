<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTag;

class PTag extends BasePTag
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
	public function countPDDebates(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::countPddTaggedTPDDebates($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPDDebates(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPddTaggedTPDDebates($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function countPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::countPuTaggedTPTags($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPUsers(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPuTaggedTPTags($con, $doQuery);
	}

}
