<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQMandate;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

/**
 *
 * @author Lionel Bouzonville
 */
class PQMandate extends BasePQMandate
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
        $toSlug =  StaticTools::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }
}
