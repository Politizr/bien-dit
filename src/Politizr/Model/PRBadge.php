<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadge;

/**
 *
 * @author Lionel Bouzonville
 */
class PRBadge extends BasePRBadge
{
    /**
     *
     * @return string
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
