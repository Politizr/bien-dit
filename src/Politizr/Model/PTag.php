<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTag;

use StudioEcho\Lib\StudioEchoUtils;

/**
 * Tag object model
 *
 * @author Lionel Bouzonville
 */
class PTag extends BasePTag
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
        $toSlug = StudioEchoUtils::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }
    
    /* ######################################################################################################## */
    /*                                                   USERS                                                  */
    /* ######################################################################################################## */
    
    /**
     * @see parent::countPuTaggedTPTags
     */
    public function countPUsers(\PropelPDO $con = null, $doQuery = true)
    {
        return parent::countPuTaggedTPTags($con, $doQuery);
    }

    /**
     * @see parent::getPuTaggedTPTags
     */
    public function getPUsers(\PropelPDO $con = null, $doQuery = true)
    {
        return parent::getPuTaggedTPTags($con, $doQuery);
    }
}
