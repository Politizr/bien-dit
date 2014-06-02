<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUQualification;

class PUQualification extends BasePUQualification
{
	/**
	 *
	 */
	public function __toString()
	{
		return $this->getTitle() . " du " . $this->getBeginAt('d/m/Y') . " au " . $this->getEndAt('d/m/Y');
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

}
