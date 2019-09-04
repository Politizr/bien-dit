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
     * Overide to manage update published doc without updating slug
     * Overwrite to fully compatible MySQL 5.7
     * note: original "makeSlugUnique" throws Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
     *
     * @see BasePDDebate::createSlug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $slug . '-' . uniqid();

        return $slug;
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
