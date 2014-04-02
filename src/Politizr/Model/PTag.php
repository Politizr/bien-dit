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



	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
	public function getBlockDebates() {
	}
}
