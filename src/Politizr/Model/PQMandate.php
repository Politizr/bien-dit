<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQMandate;

class PQMandate extends BasePQMandate
{
	/**
	 *
	 */
	public function __toString()
	{
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

}
