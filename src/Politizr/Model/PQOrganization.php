<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQOrganization;

use Politizr\Constant\PathConstants;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

/**
 * Organization object model
 *
 * @author Lionel Bouzonville
 */
class PQOrganization extends BasePQOrganization
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getInitials();
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

    /**
     * Return type + title of current orga
     *
     * @return string
     */
    public function getTypeAndTitle()
    {
        return sprintf('[%s] %s', $this->getPQType()->getTitle(), $this->getTitle());
    }
}
